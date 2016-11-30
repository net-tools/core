<?php

// namespace
namespace Nettools\Core\Containers;



// pool handling
final class Pool
{
	// [---- PROTECTED DECLARATIONS ----

	protected $_poolItems = NULL;
	protected $_inUseItems = NULL;
	protected $_factorymethod = NULL;
	protected $_factorymethodparams = NULL;
	protected $_initmethod = NULL;

	// ---- PROTECTED DECLARATIONS ----]
	

	// constructor : we must provide a factory method (to create a new fresh item), its parameters and an init method (used to wipe a re-used item before extracting it from the pool)
	public function __construct($factorymethod, $factorymethodparams = NULL, $initmethod = NULL)
	{
		$this->_poolItems = array();
		$this->_inUseItems = array();
		$this->_factorymethod = $factorymethod;
		$this->_factorymethodparams = is_null($factorymethodparams) ? array() : $factorymethodparams;
		$this->_initmethod = $initmethod;
	}
	
	
	// get an instance from the pool (new one or available one reused)
	public function get()
	{
		// if at least an item is available in the pool
		if ( count($this->_poolItems) )
			$item = array_pop($this->_poolItems);
		
		// otherwise, we create a new instance
		else
			$item = call_user_func_array($this->_factorymethod, $this->_factorymethodparams);

		// now in use
		$this->_inUseItems[] = $item;
		
		// initialize item
		if ( $this->_initmethod )
			$item->{$this->_initmethod}();
			
		return $item;
	}
	
		
	// free an item, and replace it in the pool
	public function release($item)
	{
		// search the item in the used items array
		$k = array_search($item, $this->_inUseItems);
		if ( $k === FALSE )
			return;
			
		// remove it from the used items and adding it to the pool
		unset($this->_inUseItems[$k]);
		$this->_poolItems[] = $item;
	}
}

?>