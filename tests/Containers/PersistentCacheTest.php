<?php

use \Nettools\Core\Containers\PersistentCache;
use \Nettools\Core\Containers\FileCachePersistenceProvider;



class PersistentCacheTest extends PHPUnit_Framework_TestCase
{
    protected static $_persistentCacheProvider = NULL;
    protected static $_cacheFile = NULL;
    
    
    static public function setUpBeforeClass()
    {
        self::$_cacheFile = tempnam(sys_get_temp_dir(), 'phpunit') . 'persistentCache';
        self::$_persistentCacheProvider = new FileCachePersistenceProvider(self::$_cacheFile);
    }

    
	static public function tearDownBeforeClass()
	{
		if ( file_exists(self::$_cacheFile) )
			unlink(self::$_cacheFile);
	}
    
    
    public function testRegister()
    {
		$c = new PersistentCache(self::$_persistentCacheProvider);
        
        return $c;
    }

    
    /**
     * @depends testRegister
     */
    public function testGet($c)
    {
		$this->assertEquals('val1 updated', $c->get('k1'));
		$this->assertFalse($c->get('k0'));
		$r = $c->register('knull', NULL);
		$this->assertNull($c->get('knull'));
		$this->assertNull($r);    
        
        return $c;
    }

    
}

?>