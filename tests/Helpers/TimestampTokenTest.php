<?php 

namespace Nettools\Core\Helpers\Tests;


use \Nettools\Core\Helpers\RequestSecurityHelper;
use \PHPUnit\Framework\TestCase;



class TimestampTokenTest extends TestCase
{
	function testRequestSecurityHelperTimestampToken()
	{
		// default parameters (60 seconds validity time, default secret)
		$t = RequestSecurityHelper::createTimestampToken();
		$this->assertEquals('string', gettype($t));
		$this->assertEquals(true, RequestSecurityHelper::checkTimestampToken($t));

		
		// expires in 1 second, this will fail because we sleep 2s
		$t = RequestSecurityHelper::createTimestampToken(1);
		sleep(2);
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken($t));

		
		// empty or corrupt token
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken(NULL));
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken(''));
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken('kjkk'));

		
		// token with payload
		$t = RequestSecurityHelper::createTimestampToken(15, 'mysecret', ["id"=>"this is me"]);
		$payload = NULL;
		$this->assertEquals(true, RequestSecurityHelper::checkTimestampToken($t, 'mysecret', $payload));
		$this->assertEquals(["id"=>"this is me"], $payload);
	}
}

?>