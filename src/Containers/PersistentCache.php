<?php
/**
 * PersistentCache
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Containers;




/**
 * Persistent cache
 * 
 * A strategy pattern is used to delegate the read/write operation for cache
 */
class PersistentCache extends Cache
{
	// [---- PROTECTED DECLARATIONS ----

    /**
     * @var bool Is set to true if cache has been updated and needs to be committed to storage
     */
	protected $_dirty = false;
    
    
    /**
     * @var bool Is set to true when cache has been initialized (cache content read from storage)
     */
	protected $_initialized = false;
    
    
    /**
     * @var CachePersistenceProvider Strategy to delegate read/write cache content
     */
	protected $_persistenceProvider = NULL;
	
	
	/**
     * Commit cache content to storage through persistence strategy
     */
	protected function _saveCache()
	{
		$this->_persistenceProvider->save($this->_items);
	}

	
	/**
     * Read cache content from storage through persistence strategy
     */
	protected function _readCache()
	{
		$this->_items = $this->_persistenceProvider->read();
	}
	
	
	/**
     * Initialize cache (reading it from storage if not already initialized)
     */
	protected function _initCache()
	{
		if ( !$this->_initialized )
		{
			$this->_readCache();
			$this->_initialized = true;
		}
	}

	// ---- PROTECTED DECLARATIONS ----]
	
	
	/**
     * Constructor of the persistent cache
     * 
     * @param CachePersistenceProvider $persistenceProvider Set this parameter to a strategy provider (usually to disk)
     */
	public function __construct(CachePersistenceProvider $persistenceProvider)
	{
		parent::__construct();
		$this->_persistenceProvider = $persistenceProvider;
	}
	
	
	/** 
     * Sets cache to dirty (it has been updated and we need to commit updates to storage)
     */
	public function setDirty()
	{
		$this->_initCache();
		$this->_dirty = true;
	}
	
	
	/** 
     * Is cache dirty ?
     * 
     * @return bool Returns true if cache has been updated and must be committed to storage
     */
	public function isDirty()
	{
		return $this->_dirty;
	}
	
	
	public function register($k, $item)
	{
		$this->_initCache();
		$this->_dirty = true;
		return parent::register($k, $item);
	}


	public function unregister($k)
	{
		$this->_initCache();
		$this->_dirty = true;
		return parent::unregister($k);
	}
	
	
	public function clear()
	{
		$this->_dirty = true;
		$this->_initialized = true;
		return parent::clear();
	}
		
	
	public function get($k)
	{
		$this->_initCache();
		return parent::get($k);
	}
	
	
	public function test($k)
	{
		$this->_initCache();
		return parent::test($k);
	}
	
	
	public function getCount()
	{
		$this->_initCache();
		return parent::getCount();
	}


	/**
     * Commit cache content updates to storage
     */
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