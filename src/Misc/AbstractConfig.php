<?php


namespace Nettools\Core\Misc;



/**
 * Helper class for config data
 */
abstract class AbstractConfig {
	
	
	/**
	 * Get value
	 *
	 * @param string $k Config value key name
	 * @return string
	 * @throws \Exception Exception thrown if value named $k does not exist
	 */
	abstract public function get($k);
	
	

	/**
	 * Magic accessor
	 */
	public function __get($k)
	{
		return $this->get($k);
	}
	
	
	
	/** 
	 * Test config value key exists
	 * @param string $k Config value key name
	 *
	 * @return bool
	 */
	abstract public function test($k);
}


?>