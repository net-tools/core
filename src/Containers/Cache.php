<?php
/**
 * Cache
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */



// namespace
namespace Nettools\Core\Containers;



/**
 * Base class for cache handling
 */
class Cache
{
	// [---- PROTECTED DECLARATIONS ----

    /** 
     * @var array Cache items in an array
     */
	protected $_items = NULL;

	// ---- PROTECTED DECLARATIONS ----]
	

	/**
     * Cache constructor
     */
	public function __construct()
	{
		$this->_items = array();
	}
	
	
	/**
     * Register new item in cache
     * 
     * @param string $k Key for registered item
     * @param mixed $item Item value
     * @return mixed Returns $îtem
     */
	public function register($k, $item)
	{
		$this->_items[$k] = $item;
		return $item;
	}


	/**
     * Delete an item from cache
     * 
     * @param string $k Item key to remove
     * @return bool|mixed $item Returns the item removed or FALSE if item not found in cache
     */
	public function unregister($k)
	{
		if ( array_key_exists($k, $this->_items) )
		{
			$item = $this->_items[$k];
			unset($this->_items[$k]);
			return $item;
		}
		else
			return FALSE;
	}
	
	
	/** 
     * Fetch an item from cache
     *
     * @param string $k Item to fetch
     * @return bool|mixed $item Returns the item or FALSE if item not found in cache
     */
	public function get($k)
	{
		if ( array_key_exists($k, $this->_items) )
			return $this->_items[$k];
		else
			return FALSE;
	}
	
	
	/**
     * Test if an item exists in cache
     * 
     * @param string $k Item to test
     * @return bool Returns whether the item exists in the cache
     */
	public function test($k)
	{
		return array_key_exists($k, $this->_items);
	}
	
	
	/**
     * Get the amount of items in cache
     * 
     * @return int Number of items registered
     */
	public function getCount()
	{
		return count($this->_items);
	}
	
	
	/**
     * Empty cache
     */
	public function clear()
	{
		$this->_items = array();
	}
}

?>