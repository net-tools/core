<?php

// namespace
namespace Nettools\Core\Containers;




// persistent cache (a strategy pattern is used to delegate the read/write operation for cache)
class PersistentCache extends Cache
{
	// [---- PROTECTED DECLARATIONS ----

	protected $_dirty = false;
	protected $_initialized = false;
	protected $_persistenceProvider = NULL;
	
	
	// commit cache
	protected function _saveCache()
	{
		$this->_persistenceProvider->save($this->_items);
	}

	
	// read cache
	protected function _readCache()
	{
		$this->_items = $this->_persistenceProvider->read();
	}
	
	
	// initialize the cache
	protected function _initCache()
	{
		if ( !$this->_initialized )
		{
			$this->_readCache();
			$this->_initialized = true;
		}
	}

	// ---- PROTECTED DECLARATIONS ----]
	
	
	// constructor, with a strategy to persist it (generally on disk)
	public function __construct(CachePersistenceProvider $persistenceProvider)
	{
		parent::__construct();
		$this->_persistenceProvider = $persistenceProvider;
	}
	
	
	// is cache dirty ?
	public function setDirty()
	{
		$this->_dirty = true;
	}
	
	
	// register an item in cache (lazy initialization)
	public function register($k, $item)
	{
		$this->_initCache();
		$this->_dirty = true;
		return parent::register($k, $item);
	}


	// delete an item from cache (lazy initialization)
	public function unregister($k)
	{
		$this->_initCache();
		$this->_dirty = true;
		return parent::unregister($k);
	}
	
	
	// empty cache
	public function clear()
	{
		$this->_dirty = true;
		$this->_initialized = true;
		return parent::clear();
	}
		
	
	// get an item from cache (lazy initialization)
	public function get($k)
	{
		$this->_initCache();
		return parent::get($k);
	}
	
	
	// test if an item exists in cache
	public function test($k)
	{
		$this->_initCache();
		return parent::test($k);
	}
	
	
	// number of items in cache
	public function getCount()
	{
		$this->_initCache();
		return parent::getCount();
	}


	// commit updates
	public function commit()
	{
		if ( $this->_initialized && $this->_dirty )
		{
			$this->_saveCache();
			$this->_dirty = false;
		}
	}
}

?>