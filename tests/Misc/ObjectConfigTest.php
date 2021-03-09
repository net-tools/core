<?php


namespace Nettools\Core\Misc\Tests;



class ObjectConfigTest extends \PHPUnit\Framework\TestCase
{
	
    public function testMissingArgumentConstructor()
    {
     	$this->expectException(\ArgumentCountError::class);
		
		
        $cfg = new \Nettools\Core\Misc\ObjectConfig();
        $cfg = $cfg;
    }
    
	
	
	public function testObject()
	{
        $cfg = new \Nettools\Core\Misc\ObjectConfig((object)
											array(
												'value1'	=> 12,
												'value2'	=> 'string',
												'emptyval'	=> '',
												'nullvalue'	=> NULL
											)
										);
		
		$this->assertEquals(12, $cfg->value1);
		$this->assertEquals(12, $cfg->get('value1'));
		$this->assertEquals('string', $cfg->value2);
		$this->assertEquals('', $cfg->emptyval);
		$this->assertEquals(NULL, $cfg->nullvalue);		
		$this->assertEquals(true, $cfg->test('value1'));
		$this->assertEquals(false, $cfg->test('value0'));
	}
	
	
	
    public function testInexistantProperty()
    {
		$this->expectException(\Exception::class);
		
		
        $cfg = new \Nettools\Core\Misc\ObjectConfig((object)
											array(
												'value1'	=> 1
											)
										);
		
		// property does not exist
		$cfg->value2;
    }
    
	
	
}



?>
