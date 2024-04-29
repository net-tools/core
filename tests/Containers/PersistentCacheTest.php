<?php

namespace Nettools\Core\Tests;




use \Nettools\Core\Containers\PersistentCache;
use \Nettools\Core\Containers\FileCachePersistenceProvider;
use \org\bovigo\vfs\vfsStream;
use \org\bovigo\vfs\vfsStreamDirectory;
use \PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\Attributes\Depends;




class PersistentCacheTest extends TestCase
{
    protected static $_persistentCacheProvider = NULL;
    protected static $_cacheFile = NULL;
	protected static $_vfs = NULL;
    
    
    static public function setUpBeforeClass() :void
    {
		self::$_vfs = vfsStream::setup('root');
		self::$_cacheFile = vfsStream::url('root/persistentCache');
        self::$_persistentCacheProvider = new FileCachePersistenceProvider(self::$_cacheFile);
    }

    
    public function testNewCache()
    {
		$tmp = vfsStream::url('root/persistentNewCache');
		$c = new PersistentCache(new FileCachePersistenceProvider($tmp));
       
        // cache not committed to disk yet
        $this->assertFileDoesNotExist($tmp);
        
        // assert get method returns FALSE (cache file does not exists)
        $this->assertEquals(false, $c->get('key'));

        // cache GET method does not commit cache to disk
        $this->assertFileDoesNotExist($tmp);
    }
    
    
    public function testRegister()
    {
		$c = new PersistentCache(self::$_persistentCacheProvider);
        
        // cache not committed to disk yet
        $this->assertFileDoesNotExist(self::$_cacheFile);
        
        // commit cache (currently empty) to disk ; setting Dirty to TRUE (a cache not dirty is not committed to disk)
        $c->setDirty(true);
        $c->commit();
        
        // assert cache file exists
        $this->assertFileExists(self::$_cacheFile);
        $size = filesize(self::$_cacheFile);
		
        // assert init values
        $this->assertEquals(0, $c->getCount());
        $this->assertEquals(false, $c->isDirty());
        
        // register an item in cache, check that the persistent cache is now dirty
        $this->assertEquals('value1', $c->register('k1', 'value1'));
        $this->assertEquals(1, $c->getCount());
        $this->assertEquals(true, $c->isDirty());
        
        // assert that dirty cache is not committed to disk yet
        $this->assertEquals($size, filesize(self::$_cacheFile));
        
        // commit updates and check that the cache is no longer dirty and the cache file is bigger (contains 1 item)
        $c->commit();
        $this->assertEquals(1, $c->getCount());
        $this->assertEquals(false, $c->isDirty());
        
        // in order to have the new value of filesize, we need to clear the stat cache
        clearstatcache();
        $this->assertNotEquals($size, filesize(self::$_cacheFile));
        
        return $c;
    }

    
	#[Depends('testRegister')]
    public function testGet($c)
    {
		$this->assertEquals('value1', $c->get('k1'));
		$this->assertFalse($c->get('k0'));
        $this->assertEquals(false, $c->isDirty());
        
        return $c;
    }


	#[Depends('testGet')]
    public function testUnregister($c)
    {
        $size = filesize(self::$_cacheFile);

        $this->assertEquals(1, $c->getCount());
        $this->assertEquals('value1', $c->unregister('k1'));
        $this->assertFalse($c->get('k1'));
        $this->assertEquals(0, $c->getCount());
        $this->assertEquals(true, $c->isDirty());

        // in order to have the new value of filesize, we need to clear the stat cache
        clearstatcache();

        // assert that dirty cache is not committed to disk yet
        $this->assertEquals($size, filesize(self::$_cacheFile));
        
        // commit updates and check that the cache is no longer dirty and the cache file is smaller (now contains 0 item)
        $c->commit();
        $this->assertEquals(false, $c->isDirty());

        // in order to have the new value of filesize, we need to clear the stat cache
        clearstatcache();
        $this->assertNotEquals($size, filesize(self::$_cacheFile));
        
        return $c;
    }
    
    
	#[Depends('testGet')]
    public function testClear($c)
    {
        $c->register('k2', 'value to be deleted');
        $this->assertEquals(1, $c->getCount());
        $this->assertEquals(true, $c->isDirty());
        
        // commit updates to disk
        $c->commit();

        // in order to have the new value of filesize, we need to clear the stat cache
        clearstatcache();
        $size = filesize(self::$_cacheFile);
        
        // clear cache (not committed to disk yet)
        $c->clear();
        $this->assertEquals(0, $c->getCount());
        $this->assertEquals(true, $c->isDirty());

        // commit updates and check that the cache is no longer dirty and the cache file is smaller (now contains 0 item)
        $c->commit();
        $this->assertEquals(false, $c->isDirty());

        // in order to have the new value of filesize, we need to clear the stat cache
        clearstatcache();
        $this->assertNotEquals($size, filesize(self::$_cacheFile));
    }    

}

?>