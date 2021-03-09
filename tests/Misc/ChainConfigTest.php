<?php


namespace Nettools\Core\Misc\Tests;



class ChainConfigTest extends \PHPUnit\Framework\TestCase
{
	public function testObject()
	{
        $cfg1 = new \Nettools\Core\Misc\ObjectConfig((object)
											array(
												'value1'	=> 12,
												'value2'	=> 'string'
											)
										);
        $cfg2 = new \Nettools\Core\Misc\ObjectConfig((object)
											array(
												'value3'	=> 34
											)
										);
		
		$cfg = new \Nettools\Core\Misc\ChainConfig($cfg1, $cfg2);
		
		$this->assertEquals(12, $cfg->value1);
		$this->assertEquals('string', $cfg->value2);
		$this->assertEquals(34, $cfg->value3);
		
		$this->assertEquals(true, $cfg->test('value1'));
		$this->assertEquals(true, $cfg->test('value2'));
		$this->assertEquals(true, $cfg->test('value3'));
		$this->assertEquals(false, $cfg->test('value4'));
	}
}



?>
