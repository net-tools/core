<?php

// namespace
namespace Nettools\Core\Helpers\RequestSecurityHelper;




/**
 * Unit test class to interface with client 
 */
class ArrayCacheClient implements ClientInterface
{
	public $cache = array();
	
	
	
	/**
	 * Get a value
	 * 
	 * @param string $k Value key
	 * @return string Returns value with key $k
	 */
	public function get($k)
	{
		return $this->cache[$k];
	}
	
	
	
	/**
	 * Sets a value
	 * 
	 * @param string $k Value key
	 * @param string $v Value to set with key $k
	 */
	public function set($k, $v)
	{
		$this->cache[$k] = $v;
	}
	
	
	
	/**
	 * Delete a value
	 *
	 * @param string $k Value key
	 */	
	public function delete($k)
	{
		unset($this->cache[$k]);
	}
}