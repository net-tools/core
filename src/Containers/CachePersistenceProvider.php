<?php

// namespace
namespace Nettools\Core\Containers;



// strategy interface to implement read/save operations on a persistent cache
interface CachePersistenceProvider
{
	public function read();
	public function save($data);
}


?>