<?php

namespace \Nettools\Core\Tests;




use \Nettools\Core\Helpers\NetworkingHelper;




class NetworkingHelperTest extends \PHPUnit\Framework\TestCase
{
    
    public function testAppendToUrl()
    {
		$this->assertEquals('http://www.test.com/index.php?key=value', NetworkingHelper::appendToUrl('http://www.test.com/index.php', 'key=value'));
		$this->assertEquals('http://www.test.com/index.php?k=v&key=value', NetworkingHelper::appendToUrl('http://www.test.com/index.php?k=v', 'key=value'));
		$this->assertEquals('http://www.test.com/index.php?k=v&k2=v2&key=value', NetworkingHelper::appendToUrl('http://www.test.com/index.php?k=v&k2=v2', 'key=value'));
		$this->assertEquals('http://www.test.com/index.php?k=v&key=value#test', NetworkingHelper::appendToUrl('http://www.test.com/index.php?k=v#test', 'key=value'));
		$this->assertEquals('http://www.test.com/index.php?key=value#test', NetworkingHelper::appendToUrl('http://www.test.com/index.php#test', 'key=value'));    
    }

    
}

?>