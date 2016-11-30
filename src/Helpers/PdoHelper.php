<?php

namespace Nettools\Core\Helpers;



// helper class extending PDO class to deal with tables
class PdoHelper extends \PDO
{
	// [------- PROTECTED DECLARATIONS 
	
	/*
	FK_TABLES = [
					'Towns' => 	[
									'primarykey' => 'idTown',
									'tables' =>	[
													'Customer',
													
													'Vendor',
													
													[
														'table' 	=> 'Merchants',
														'sqlcond'	=> 'active=1'
													]
												]
									
								]
				]
	*/
	protected $_foreignKeys = array();
	
	
	// PROTECTED DECLARATIONS   -------]
	
    
    
	// add some config data for a new foreign key ; $TABLE is the foreign key table, $PKNAME the name of its primary key, and $TABLES is an array of tables referencing $TABLE
	function addForeignKey($table, $pkname, $tables)
	{
		if ( is_string($tables) )
			$tables = array($tables);
			
		
		$this->_foreignKeys[$table] = array(
					'primaryKey' => $pkname,
					'tables' => $tables
				);
	}
	
	
	// remove config data about a foreign key
	function deleteForeignKey($table)
	{
		unset($this->_foreignKeys[$table]);
	}
	
	
	// get a config definition for a foreign key with a SQL condition
	static function foreignKeySQLWhere($table, $sql)
	{
		return array('table'=>$table, 'sqlcond'=>$sql);
	}
	
	
	// sql request (DELETE, INSERT, UPDATE)
	function pdo_query($query, $values = NULL)
	{
		if ( !is_null($values) && !is_array($values) )
			$values = array($values);
		
		return $this->prepare($query)->execute($values);
	}

	
	// SQL SELECT request ; returns a PDOStatement if OK, otherwise and exception is thrown
	function pdo_query_select($query, $values = NULL)
	{
		if ( !is_null($values) && !is_array($values) )
			$values = array($values);
		
		$st = $this->prepare($query);
		$st->execute($values);
		
		return $st;
	}

	
	// get a single value fetched with a SELECT query (first column) ; if more than one line is returned by the request, only the first one is used
    // if an exception is thrown by PDO, it is intercepted and false is returned
    function pdo_dbexists($query, $values = NULL)
	{
		try
		{
			if ( !is_null($values) && !is_array($values) )
				$values = array($values);
	
			$st = $this->prepare($query);
			$st->execute($values);
			$r = $st->fetch(\PDO::FETCH_COLUMN, 0);
			$st->closeCursor();
			return $r;		
		}
		catch ( \PDOException $e )
		{
			return FALSE;
		}
	}

	
	// get a value (first column of row) from a PDO Statement (to be executed with values, if given)
    // if an exception is thrown by PDO, it is intercepted and false is returned
	static function pdo_value(\PDOStatement $st, $values = NULL)
	{
		try
		{
			if ( !is_null($values) && !is_array($values) )
				$values = array($values);
	
			$st->execute($values);
			$r = $st->fetch(\PDO::FETCH_COLUMN, 0);
			$st->closeCursor();
			return $r;		
		}
		catch ( \PDOException $e )
		{
			return FALSE;
		}
	}

	
	// get the next available INTERGER primary key
	function pdo_dbincrement($table, $col)
	{
		$result = $this->pdo_dbexists("SELECT MAX($col)+1 FROM $table");
		
		// if table is empty, we have a NULL value
		if ( is_null($result) )
			return 1;
		else
			return $result;	
	}


	// sum rows on one column from a PDO statement (to be executed with parametered values, if given)
	static function pdo_dbsum(\PDOStatement $result, $col, $values = NULL)
	{
		$tot = 0.0;
		$result->execute($values);
	
		if ( $result->rowCount() )
		{
			$fetch = is_string($col) ? \PDO::FETCH_ASSOC : \PDO::FETCH_NUM;
			while ( $row = $result->fetch($fetch) )
				$tot += $row[$col];
		}
	
		$result->closeCursor();
		return $tot;
	}
	
	
	// test if a given primary key value from a table is referenced by other tables (foreign key is used)
	function pdo_foreignkeys($tablefk, $keyvalue)
	{
		$fk_tables = $this->_foreignKeys;
		
		
		try
		{
			// if we have no data about this table in the foreign key config, we are fine
			if ( !$fk_tables[$tablefk] )
				return array('statut'=>true);
				

			// get the list of tables having $TABLEFK as a foreign key
			$tablesfk = $fk_tables[$tablefk]['tables'];
            
            // get the primary key name for $TABLEFK
			$pk = $fk_tables[$tablefk]['primaryKey'];
			
            
            $foreignkeys = array();
			
            // for all tables having $TABLEFK as a foreign key
			foreach ( $tablesfk as $row )
			{
				// get table name ; if we have an array instead of a string, the foreign key is valid with an additionnal SQL condition
				$tname = is_array($row) ? $row['table'] : $row;
				
				// are there rows in this table having a column referencing $TABLEFK with $KEYVALUE ?
				$query = "SELECT `$pk` FROM $tname WHERE `$pk` = :keyvalue";
	
				// if SQL condition is provided, use it
				if ( is_array($row) )
					$query .= " AND " . $row['sqlcond'];

				// execute request
				$result2 = $this->pdo_query_select($query, array(':keyvalue'=>$keyvalue));
				
				// if at least on line matching, the foreign key is used
				if ( $result2->rowCount() )
					$foreignkeys[] = $tname;
				
				$result2->closeCursor();
			}
			
			
			// we now have a list of tables referencing our foreign key $TABLEFK
			if ( count($foreignkeys) )
				return array(
					'statut'=>false, 
					'cause'=>array(
								'message'=>"Foreign key '$keyvalue' exists in table(s) : " . implode(", ", $foreignkeys),
								'tables'=>$foreignkeys
						));
			else
				return array('statut'=>true);
		}
		catch (\PDOException $e)
		{
			return array('statut'=>false, "cause"=>array("message"=>$e->getMessage()));
		}
	}
}


?>