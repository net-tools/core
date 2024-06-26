<?php

namespace Nettools\Core\Tests;



use \Nettools\Core\Containers\Cache;
use \PHPUnit\Framework\Attributes\Depends;
use \PHPUnit\Framework\TestCase;




class CacheTest extends TestCase
{
    public function testRegister()
    {
		$c = new Cache();
		$this->assertEquals(0, $c->getCount());
		$r = $c->register('k1', 'val1');
		$this->assertEquals('val1', $r);
		$this->assertEquals(1, $c->getCount());
		$c->register('k1', 'val1 updated');
		$this->assertEquals(1, $c->getCount());
        
        return $c;
    }

    
	#[Depends('testRegister')]
    public function testGet($c)
    {
		$this->assertEquals('val1 updated', $c->get('k1'));
		$this->assertFalse($c->get('k0'));
		$r = $c->register('knull', NULL);
		$this->assertNull($c->get('knull'));
		$this->assertNull($r);    
        
        return $c;
    }

    
	#[Depends('testGet')]
    public function testUnregister($c)
    {
		$r = $c->unregister('knull');
		$this->assertFalse($c->get('knull'));
		$this->assertNull($r);
		$this->assertEquals(1, $c->getCount());
        
        return $c;
    }

    
	#[Depends('testUnregister')]
    public function testClear($c)
    {
		$c->clear();
		$this->assertEquals(0, $c->getCount());
		$this->assertFalse($c->get('k1'));    
    }
}

?>