<?php

use \Nettools\Core\Containers\Pool;




class PoolUnitTestObject
{
	public $data = NULL;
	public static $creationCount = 0;
	
	
	public function __construct()
	{
		self::$creationCount++;
	}
	
	
	public function initialize()
	{
		$this->data = NULL;
	}
	
	
	static function create()
	{
		return new PoolUnitTestObject();
	}
}





class PoolTest extends PHPUnit\Framework\TestCase
{
    public function testPool()
    {
		$p = new Pool(array('PoolUnitTestObject', 'create'), array(), 'initialize');
		$o = $p->get();
		$o->data = 'data-o';
		
        $this->assertInstanceOf('PoolUnitTestObject', $o);
		$this->assertEquals(1, PoolUnitTestObject::$creationCount);
		$o2 = $p->get();
		$o2->data = 'data-o2';
		$this->assertEquals(2, PoolUnitTestObject::$creationCount);
        

        $p->release($o);
		$this->assertEquals(2, PoolUnitTestObject::$creationCount);
		$o = $p->get();
		$this->assertNull($o->data);
		$this->assertEquals(2, PoolUnitTestObject::$creationCount);
		$p->release($o);
		$p->release($o2);
		$o = $p->get();
		$o2 = $p->get();
		$this->assertEquals(2, PoolUnitTestObject::$creationCount);
		$o3 = $p->get();
		$this->assertNull($o3->data);
		$this->assertEquals(3, PoolUnitTestObject::$creationCount);
		
    }

    
}

?>