<?php


namespace Nettools\Core\Misc;



/**
 * Helper for config data as object litteral (useful for unit tests)
 */
class ObjectConfig extends AbstractConfig{
	
	
	protected $_object;
	
	
	
	/** 
	 * Constructor
	 *
	 * @param \stdClass $o
	 */
	public function __construct(\stdClass $o)
	{
		$this->_object = $o;
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
		if ( !$this->test($k) )
			throw new \Exception("Config value '$k' does not exist");

		return $this->_object->{$k};
	}
	
	
	
	/** 
	 * Test config value key exists
	 * @param string $k Config value key name
	 *
	 * @return bool
	 */
	public function test($k)
	{
		return property_exists($this->_object, $k);
	}
}


?>