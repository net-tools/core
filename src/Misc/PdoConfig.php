<?php


namespace Nettools\Core\Misc;



/**
 * Helper for config data from Pdo
 */
class PdoConfig extends AbstractConfig{
	
	
	protected $_qst;
	protected $_prefix;
	
	
	/** 
	 * Constructor
	 *
	 * @param \PDOStatement $pdo_statement Prepared request to fetch a config value ; use a ? placeholder for value name
	 * @param string $prefix Prefix to use before each value name
	 */
	public function __construct(\PDOStatement $pdo_statement, $prefix = '')
	{
		$this->_qst = $pdo_statement;
		$this->_prefix = $prefix;
	}
	
	
	
	/**
	 * Get value
	 *
	 * @param string $k Value key
	 * @return string
	 * @throws \Exception Throw if value does not exist
	 */
	public function get($k)
	{
		try
		{
			$this->_qst->execute(array($this->_prefix . $k));
			$value = $this->_qst->fetchColumn(0);
			if ( $value === FALSE )
				throw new \Exception("Config value '$k' does not exist");
			
			return $value;
		}
		catch (\PDOException $e)
		{
			throw new \Exception("Config value '$k' can't be read (SQL error {$e->getMessage()})");
		}		
	}
}


?>