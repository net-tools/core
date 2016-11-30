<?php

// namespace
namespace Nettools\Core\Containers;



// strategy class implementing read/write operations a persistent FILE cache 
class FileCachePersistenceProvider implements CachePersistenceProvider
{
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
	
	
	public function __construct($f)
	{
		$this->_filename = $f;
	}
}

?>