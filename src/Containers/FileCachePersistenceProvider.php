<?php
/**
 * FileCachePersistentProvider
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



// namespace
namespace Nettools\Core\Containers;



/** 
 * Strategy interface to implement read/save operations for a persistent cache on a file
 */
class FileCachePersistenceProvider implements CachePersistenceProvider
{
    /** 
    * @var string Filename of cache content storage on disk 
    */
	protected $_filename = NULL;
	
	
	public function read()
	{
		if ( file_exists($this->_filename) )
			return unserialize(file_get_contents($this->_filename));
		else	
			return array();
	}
	
	
	public function save($data)
	{
		$f = fopen($this->_filename, 'w');
		fwrite($f, serialize($data));
		fclose($f);
	}
	
	
    /** 
     * Constructor of strategy
     * 
     * @param string $f Filename for file storage on disk
     */
	public function __construct($f)
	{
		$this->_filename = $f;
	}
}

?>