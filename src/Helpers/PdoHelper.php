<?php

/**
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



namespace Nettools\Core\Helpers;



/**
 * Helper class extending PDO class to deal with tables
 */
class PdoHelper extends \PDO
{
	// [------- PROTECTED DECLARATIONS 
	
	/**
     * Schema for foreign keys
     *
     * This is a simple schema : Towns being a foreign key for 3 tables, Customer, Vendor and Merchants :
     *
     *     [
     *      'Towns' => 	[
     *                      'primarykey' => 'idTown',
     *              		'tables' =>	[
     *                              'Customer',
     *              													
     *                              'Vendor',
     *              													
     *              				[
     *              				    'table' 	=> 'Merchants',
     *              					'sqlcond'	=> 'active=1'
     *              			    ]
     *              		]
     *              									
     *              	]
     *     ]
     *
	 */
	protected $_foreignKeys = array();
		
	// PROTECTED DECLARATIONS   -------]
	
    
    
	/**
     * Add some config data for a new foreign key
     * 
     * @param string $table Name of the foreign key table
     * @param string $pkname Name of its primary key
     * @param string[] $tables Array of tables referencing $table parameter
     */
	function addForeignKey($table, $pkname, $tables)
	{
		if ( is_string($tables) )
			$tables = array($tables);
			
		
		$this->_foreignKeys[$table] = array(
					'primaryKey' => $pkname,
					'tables' => $tables
				);
	}
	
	
	/**
     * Remove config data about a foreign key
     *
     * @param string $table Table name to remove
     */
	function deleteForeignKey($table)
	{
		unset($this->_foreignKeys[$table]);
	}
	
	
	/**
     * Get a config definition for a foreign key with a SQL condition
     * 
     * @param string $table Table name
     * @param string $sql SQL condtion ; if true, the foreign key is valid and you may not delete the row
     */
	static function foreignKeySQLWhere($table, $sql)
	{
		return array('table'=>$table, 'sqlcond'=>$sql);
	}
	
	
	/** 
     * Helper query method to prepare and execute a sql request (DELETE, INSERT, UPDATE)
     * 
     * @param string $query The SQL query
     * @param string[] $values An array of parameters for the query (? and :xxx placeholders concrete values)
     * @return bool Returns true if query OK
     * @throws \PDOException If an error occured, a exception is thrown
     */
	function pdo_query($query, $values = NULL)
	{
		if ( !is_null($values) && !is_array($values) )
			$values = array($values);
		
		return $this->prepare($query)->execute($values);
	}

	
	/**
     * Helper query method for a SQL SELECT request 
     *
     * @param string $query The SQL query
     * @param string[] $values An array of parameters for the query (? and :xxx placeholders concrete values)
     * @return \PDOStatement A PDOStatement object with rows to fetch
     * @throws \PDOException If an error occured, a exception is thrown
     */
	function pdo_query_select($query, $values = NULL)
	{
		if ( !is_null($values) && !is_array($values) )
			$values = array($values);
		
		$st = $this->prepare($query);
		$st->execute($values);
		
		return $st;
	}

	
	/** 
     * Get a single value fetched with a SELECT query (first column) 
     * 
     * If more than one line is returned by the request, only the first one is used ;
     * If an exception is thrown by PDO, it is intercepted and false is returned
     * 
     * @param string $query The SQL query
     * @param string[] $values An array of parameters for the query (? and :xxx placeholders concrete values)
     * @return int|float|string|bool The value selected by the SQL $query or FALSE if an error occured
     */
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

	
	/** 
     * Gt a value (first column of row) from a PDO Statement (to be executed with values, if given)
     * 
     * If more than one line is returned by the request, only the first one is used ;
     * If an exception is thrown by PDO, it is intercepted and false is returned
     * 
     * @param \PDOStatement $st The PDO statement, already prepared
     * @param string[] $values An array of parameters for the query (? and :xxx placeholders concrete values)
     * @return int|float|string|bool The value selected by the SQL $query or FALSE if an error occured
     */
	function pdo_value(\PDOStatement $st, $values = NULL)
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

	
	/**
     * Get the next available INTERGER primary key
     * 
     * @param string $table Table name
     * @param string $col Primary key column name
     * @return int Next available integer primary key for this table
     */
	function pdo_dbincrement($table, $col)
	{
		$result = $this->pdo_dbexists("SELECT MAX($col)+1 FROM $table");
		
		// if table is empty, we have a NULL value
		if ( is_null($result) )
			return 1;
		else
			return $result;	
	}


	/** 
     * Sum rows on one column from a PDO statement (to be executed with parametered values, if given)
     * 
     * @param \PDOStatement $result SQL statement, already prepared
     * @param int|string $col The column to sum (a column name or a column index)
     * @param string[] $values An array of parameters for the query (? and :xxx placeholders concrete values)
     */
	function pdo_dbsum(\PDOStatement $result, $col, $values = NULL)
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
	
	
	/**
     * Test if a given primary key value from a table is referenced by other tables (foreign key is used)
     * 
     * @param string $tablefk Table name of the table being foreign key
     * @param string $keyvalue Value of the primary key to look for
     * @return string[] Array describing the result `['statut'=>true]` if the foreign key is not used, `['statut'=>false]` otherwise
     *      In the latter case, the array will contain additionnal data : `['cause'=> ['message'=>'error message', 'tables'=>[...table names...]]`
     */
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