<?php

// namespace
namespace Nettools\Core\Containers;



// cache handling
class Cache
{
	// [---- PROTECTED DECLARATIONS ----

	protected $_items = NULL;

	// ---- PROTECTED DECLARATIONS ----]
	

	// constructor
	public function __construct()
	{
		$this->_items = array();
	}
	
	
	// register new item in cache
	public function register($k, $item)
	{
		$this->_items[$k] = $item;
		return $item;
	}


	// delete an item from cache
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
	
	
	// fetch an item from cache
	public function get($k)
	{
		if ( array_key_exists($k, $this->_items) )
			return $this->_items[$k];
		else
			return FALSE;
	}
	
	
	// test if an item exists in cache
	public function test($k)
	{
		return array_key_exists($k, $this->_items);
	}
	
	
	// get the amount of items in cache
	public function getCount()
	{
		return count($this->_items);
	}
	
	
	// empty cache
	public function clear()
	{
		$this->_items = array();
	}
}

?>