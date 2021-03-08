<?php


namespace Nettools\Core\Misc;



/**
 * Helper for config data as two AbstractConfig objects chained (if the first doesn't have the config value asked, the search will be handled to the second config object)
 */
class ChainConfig extends AbstractConfig{
	
	
	protected $_cfg1;
	protected $_cfg2;
	
	
	
	/** 
	 * Constructor
	 *
	 * @param AbstractConfig $cfg1
	 * @param AbstractConfig $cfg2
	 */
	public function __construct(AbstractConfig $cfg1, AbstractConfig $cfg2)
	{
		$this->_cfg1 = $cfg1;
		$this->_cfg2 = $cfg2;
	}
	
	
	
	/**
	 * Get a value
	 *
	 * @param string $k Value key
	 * @return string
	 * @throws \Exception Thrown if config value $k does not exist
	 */
	public function get($k)
	{
		if ( $this->_cfg1->test($k) )
			return $this->_cfg1->get($k);
		else
			return $this->_cfg2->get($k);
	}
	
	
	
	/**
	 * Test config value key exists
	 * @param string $k Config value key name
	 *
	 * @return bool
	 */
	public function test($k)
	{
		return $this->_cfg1->test($k) || $this->_cfg2->test($k);
	}
}


?>