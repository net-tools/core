<?php 

namespace Nettools\Core\Helpers\Tests;


use \Nettools\Core\Helpers\RequestSecurityHelper;
use \PHPUnit\Framework\TestCase;



class TimestampTokenTest extends TestCase
{
	function testRequestSecurityHelperTimestampToken()
	{
		$t = RequestSecurityHelper::createTimestampToken(60, 'my_secret');
		$this->assertEquals('string', gettype($t));
		$this->assertEquals(true, RequestSecurityHelper::checkTimestampToken($t, 'my_secret'));


        // wrong secret		
		$t = RequestSecurityHelper::createTimestampToken(60, 'my_secret');
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken($t, 'error here'));

		
		// expires in 1 second, this will fail because we sleep 2s
		$t = RequestSecurityHelper::createTimestampToken(1, 'this is secret');
		sleep(2);
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken($t, 'this is secret'));

		
		// empty or corrupt token
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken('', 'this is secret'));
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken('kjkk', 'this is secret'));

		
		// token with payload
		$t = RequestSecurityHelper::createTimestampToken(15, 'mysecret', ["id"=>"this is me"]);
		$payload = NULL;
		$this->assertEquals(true, RequestSecurityHelper::checkTimestampToken($t, 'mysecret', $payload));
		$this->assertEquals("this is me", $payload['id']);
	}
}

?>