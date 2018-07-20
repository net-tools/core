<?php


namespace Nettools\Core\Misc;



/**
 * Helper for config values from another config object, with a prefix
 */
class PrefixConfig extends AbstractConfig{
	
	protected $_cfg;
	protected $_prefix;
	
	
	
	/** 
	 * Constructor
	 *
	 * @param \Nettools\Core\Misc\AbstractConfig $cfg
	 * @param string $prefix
	 */
	public function __construct(\Nettools\Core\Misc\AbstractConfig $cfg, $prefix)
	{
		$this->_cfg = $cfg;
		$this->_prefix = $prefix;
	}
	
	
	
	/**
	 * Get value
	 *
	 * @param string $k Value name
	 * @return string
	 * @throws \Exception Thrown if value doesn't exist
	 */
	public function get($k)
	{
		return $this->_cfg->{$this->_prefix . $k};
	}
}


?>