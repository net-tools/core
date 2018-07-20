<?php


namespace Nettools\Core\Misc\Tests;



class TwigObjectTest extends \PHPUnit\Framework\TestCase
{
	public function testTwigObject()
	{
        $o = new \Nettools\Core\Misc\TwigObject((object)
											array(
												'value1'	=> 12,
												'value2'	=> 'string',
												'emptyval'	=> '',
												'nullvalue'	=> NULL
											)
										);
		
		$this->assertEquals(12, $o->value1());
		$this->assertEquals(12, $o->get('value1'));
		$this->assertEquals('string', $o->value2());
		$this->assertEquals('', $o->emptyval());
		$this->assertEquals(NULL, $o->nullvalue());		
	}
	
	
	
}



?>
