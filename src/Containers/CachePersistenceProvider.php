<?php
/**
 * CachePersistentProvider
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Containers;



/** 
 * Strategy interface to implement read/save operations on a persistent cache
 */
interface CachePersistenceProvider
{
	/** 
     * Read cache content from storage
     * 
     * @return mixed Returns cache contant unserialized in an appropriate way
     */
    public function read();
    
    
    /** 
     * Save cache content to storage
     * 
     * @param mixed $data Cache content to serialize in an appropriate way and save to storage
     */
	public function save($data);
}


?>