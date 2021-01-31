<?php 

namespace Nettools\Core\Helpers\Tests;


use \Nettools\Core\Helpers\RequestSecurityHelper\JsonToken;
use \Nettools\Core\Helpers\RequestSecurityHelper\TimestampToken;
use \Nettools\Core\Helpers\RequestSecurityHelper;
use \PHPUnit\Framework\TestCase;



class TimestampTokenTest extends TestCase
{
	function testJsonToken()
	{
		$t = new JsonToken(array('k1'=>'v1', 'k2'=>2));
		$this->assertEquals('v1', $t->k1);
		$this->assertEquals(2, $t->k2);
		$this->assertNull($t->k3);


		// tester init en objet
		$t = new JsonToken((object)array('k1'=>'v1', 'k2'=>2));
		$this->assertEquals('v1', $t->k1);
		$this->assertEquals(2, $t->k2);
		$this->assertNull($t->k3);
		
		
		// tester conversion chaine json
		$this->assertEquals('string', gettype($t->toJson()));
		$this->assertEquals('{"k1":"v1","k2":2}', $t->toJson());
		$this->assertEquals(''.$t, $t->toJson());
		
		
		// tester reconstruction
		$js = '{"k1":"v1","k2":2}';
		$t = JsonToken::fromJson($js);
		$this->assertEquals('v1', $t->k1);
		$this->assertEquals(2, $t->k2);

		$js = 'zz';
		$t = JsonToken::fromJson($js);
		$this->assertNull($t);
		
	}
	
	
	
	function testTimestampToken()
	{
		$t = (new TimestampToken())->create();
		
		// token created with default values expires in 60 seconds
		$this->assertEquals(60, hexdec($t->d));
		$this->assertEquals('s', $t->u);
		
		// token created with non default values
		$t = (new TimestampToken())->create(40, 'h');
		$this->assertEquals(40, hexdec($t->d));
		$this->assertEquals('h', $t->u);
		
		// testing hash : 64 hex values
		$this->assertEquals(64, strlen($t->h), 'Wrong length for hash token part');
		$this->assertMatchesRegularExpression('/^[a-fA-F0-9]{64}$/', $t->h, 'Wrong pattern for hash token part');

		// testing delay (token validity) ; 2 digits hex values
		$this->assertEquals(2, strlen($t->d), 'Wrong length for delay token part');
		$this->assertMatchesRegularExpression('/^[a-fA-F0-9]{2}$/', $t->d, 'Wrong pattern for delay token part');

		// testing token validity unit (h/m/s) ; 1 letter
		$this->assertEquals(1, strlen($t->u), 'Wrong length for unit token part');
		$this->assertMatchesRegularExpression('/^h|m|s$/', $t->u, 'Wrong pattern for unit token part');
		
		// testing unique ID ; hex digits
		$this->assertMatchesRegularExpression('/^[a-fA-F0-9]+$/', $t->i, 'Wrong pattern for unique id token part');
		
		// testing timestamp ; hex digits
		$this->assertMatchesRegularExpression('/^[a-fA-F0-9]+$/', $t->t, 'Wrong pattern for timestamp token part');
		
	}
	
	
	
	function testCheckTimestampTokenOverflows()
	{
		// testing overflows : seconds
		$t = (new TimestampToken())->create(256, 's');
		$this->assertEquals('m', $t->u);
		$this->assertEquals(4, hexdec($t->d));		// 4 mins = round(256 secs / 60 secs)
		
				
		// testing overflows : minutes
		$t = (new TimestampToken())->create(256, 'm');
		$this->assertEquals('h', $t->u);
		$this->assertEquals(4, hexdec($t->d));		// 4 hours = round(256 mins / 60 mins)
		
		
		// testing overflows : hours
		$t = (new TimestampToken())->create(256, 'h');
		$this->assertEquals('h', $t->u);
		$this->assertEquals(255, hexdec($t->d));	// ceiled to 255 hours
	}
	
	
	
	function testCheckTimestampToken()
	{
		$gen = (new TimestampToken());
		$t = $gen->create();
		$this->assertEquals(true, $gen->check($t));
		
		
		// create token with custom expiration time
		$t = $gen->create('24', 'h');
		$this->assertEquals(true, $gen->check($t));
		
		
		// create token with 1 second expiration time
		$t = $gen->create('1', 's');
		sleep(2);
		$this->assertEquals(false, $gen->check($t), 'Assertion failed for 1s timestamp delay checking');
	}
	
	
	
	function testCheckTimestampTokenSecret()
	{
		$gen = (new TimestampToken());
		$t = $gen->create(60, 's', 'my_secret');
		$this->assertEquals(true, $gen->check($t, 'my_secret'));
		$this->assertEquals(false, $gen->check($t, 'my_different_secret'));
	}
	
	
	
	function testRequestSecurityHelperCheckTimestampToken()
	{
		$t = RequestSecurityHelper::createTimestampToken();
		$this->assertEquals('string', gettype($t));
		$this->assertEquals(true, json_decode($t) != null);
		$this->assertEquals(60, hexdec(json_decode($t)->d));
		$this->assertEquals('s', json_decode($t)->u);
		$this->assertEquals(true, RequestSecurityHelper::checkTimestampToken($t));

		
		$t = RequestSecurityHelper::createTimestampToken(1, 's');
		sleep(2);
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken($t));

		// token vide ou incorrect
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken(''));
		$this->assertEquals(false, RequestSecurityHelper::checkTimestampToken('kjkk'));
	}
	
	
	
}

?>