<?php


namespace Nettools\Core\Misc\Tests;



class ObjectConfigTest extends \PHPUnit\Framework\TestCase
{
	
    /**
     * @expectedException \ArgumentCountError
     */
    public function testMissingArgumentConstructor()
    {
        $cfg = new \Nettools\Core\Misc\ObjectConfig();
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
	}
	
	
	
    /**
     * @expectedException \Exception
     */
    public function testInexistantProperty()
    {
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
