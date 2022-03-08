<?php
/**
 * Pool
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Containers;



/**
 * Pool handling
 */
final class Pool
{
	// [---- PROTECTED DECLARATIONS ----

    /** 
     * @var array Pool items array
     */
	protected $_poolItems = NULL;
    
    /**
     * @var array Array of items used from pool
     */    
	protected $_inUseItems = NULL;
    
    /** 
     * @var callable Name of factory method to create a new item
     */
	protected $_factorymethod = NULL;
    
    /**
     * @var array Array of factory method parameters 
     */
	protected $_factorymethodparams = NULL;
    
    /**
     * @var string Init method name to wipe a pool item before re-using it 
     */
	protected $_initmethod = NULL;

	// ---- PROTECTED DECLARATIONS ----]
	

	/**
     * Pool constructor
     * 
     * @param callable $factorymethod Factory method (to create a new fresh item)
     * @param array $factorymethodparams Factory method parameters 
     * @param string $initmethod Method name to wipe a re-used item before extracting it from the pool
     */
	public function __construct($factorymethod, $factorymethodparams = NULL, $initmethod = NULL)
	{
		$this->_poolItems = array();
		$this->_inUseItems = array();
		$this->_factorymethod = $factorymethod;
		$this->_factorymethodparams = is_null($factorymethodparams) ? array() : $factorymethodparams;
		$this->_initmethod = $initmethod;
	}
	
	
	/**
     * Get an instance from the pool (new one or available one reused)
     * 
     * @return object An instance from the pool
     */
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
	
		
	/** 
     * Free an item, and replace it in the pool
     * 
     * @param object $item Item to release and replace in the pool
     */
	public function release($item)
	{
		// search the item in the used items array
		$k = array_search($item, $this->_inUseItems);
		if ( $k === FALSE )
			return;
			
		// remove it from the used items and adding it to the pool
		unset($this->_inUseItems[$k]);
		
		// reset numeric keys
		$this->_inUseItems = array_values($this->_inUseItems);
		
		// push to array released item
		$this->_poolItems[] = $item;
	}
}

?>