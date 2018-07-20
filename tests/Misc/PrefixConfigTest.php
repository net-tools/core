<?php


namespace Nettools\Core\Misc\Tests;



class PrefixConfigTest extends \PHPUnit\Framework\TestCase
{
	
	public function testObject()
	{
		$ocfg = new \Nettools\Core\Misc\ObjectConfig((object)
											array(
												'value1'	=> 12,
												'prefix_value2'	=> 'string'
											)
										);
        $cfg1 = new \Nettools\Core\Misc\PrefixConfig($ocfg, '');
        $cfg2 = new \Nettools\Core\Misc\PrefixConfig($ocfg, 'prefix_');
		
		$this->assertEquals(12, $cfg1->value1);
		$this->assertEquals('string', $cfg2->value2);
	}
	
	
	
    /**
     * @expectedException \Exception
     */
    public function testInexistantProperty()
    {
		$ocfg = new \Nettools\Core\Misc\ObjectConfig((object)
											array(
												'value1'	=> 12,
												'prefix_value2'	=> 'string'
											)
										);
        $cfg2 = new \Nettools\Core\Misc\PrefixConfig($ocfg, 'prefix_');
		$this->assertEquals('string', $cfg2->value1);
    }
    
	
	
}



?>
