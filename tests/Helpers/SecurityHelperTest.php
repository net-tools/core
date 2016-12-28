<?php

use \Nettools\Core\Helpers\SecurityHelper;



class SecurityHelperTest extends PHPUnit_Framework_TestCase
{
    
    public function testCreateToken()
    {
		$this->assertEquals(64+13, strlen(SecurityHelper::createToken('secret')));
		$tok1 = SecurityHelper::createToken('secret');
		$tok2 = SecurityHelper::createToken('secret');
		$this->assertNotEquals($tok1, $tok2);
    }


    public function testCheckToken()
    {
		$t = SecurityHelper::createToken('secret');
        $this->assertInternalType('bool', SecurityHelper::checkToken($t, 'secret'));
		$this->assertTrue(SecurityHelper::checkToken($t, 'secret'));
		$this->assertFalse(SecurityHelper::checkToken($t.'k', 'secret'));
		$this->assertFalse(SecurityHelper::checkToken('', 'secret'));
		$this->assertFalse(SecurityHelper::checkToken(NULL, 'secret'));   
    }
    
    
    public function testCreateTimestampToken()
    {
        $t = SecurityHelper::createTimestampToken();
		$this->assertInternalType('string', $t);
		$this->assertTrue(strlen($t) > 80);
		$tok1 = SecurityHelper::createTimestampToken();
		$tok2 = SecurityHelper::createTimestampToken();
		$this->assertNotEquals($tok1, $tok2);
    }

    
    public function testCheckTimestampToken()
    {
        $t = SecurityHelper::createTimestampToken();
		$this->assertInternalType('bool', SecurityHelper::checkTimestampToken($t));
		$this->assertTrue(SecurityHelper::checkTimestampToken($t));
		$this->assertFalse(SecurityHelper::checkTimestampToken($t.'k'));
		$this->assertFalse(SecurityHelper::checkTimestampToken(''));
		$this->assertFalse(SecurityHelper::checkTimestampToken(NULL));
    }
    
    
    public function testSanitize()
    {
		$s = '<tag> ab </tag> INSERT UPDATE DROP DELETE SELECT ALTER cd';
		$this->assertEquals('ab  cd', SecurityHelper::sanitize($s));
    }
    
    
    public function testSanitize_array()
    {
		$a = $a2 = array('<tag> ab </tag> INSERT UPDATE DROP DELETE SELECT ALTER cd', '<script language="javascript">test();</script>');
		$this->assertInternalType('array', SecurityHelper::sanitize_array($a));
		$this->assertEquals(array('ab  cd', 'test();'), $a);  // $a is passed by reference, so $a is already sanitiez here thanks to previous line
		$this->assertEquals(array('ab  cd', 'test();'), SecurityHelper::sanitize_array($a2)); 
    }
    
    
    public function testCleanString()
    {
		$this->assertEquals('ab-cd-aaa-eeee-ii-oo-uuu-c-AAA-EEEE-II-OO-UUU-C', SecurityHelper::cleanString('ab\'cd àäâ;ëêéè;ïî;öô;ùüû;ç;ÀÄÂ;ÉÈËÊ;ÏÎ;ÖÔ;ÙÜÛ;Ç'));
    }
    
    
    public function testCryptoJsAesEncrypt()
    {
    	$enc = SecurityHelper::cryptoJsAesEncrypt('mykey', 'secret to encrypt');
		$this->assertInternalType('string', $enc); 
		$dec = json_decode($enc, true);
		$this->assertInternalType('array', $dec);
		$this->assertArraySubset(array('ct', 'iv', 's'), array_keys($dec));

		$dec = SecurityHelper::cryptoJsAesDecrypt('mykey', $enc);
		$this->assertInternalType('string', $dec);
		$this->assertEquals('secret to encrypt', $dec);
    }
}

?>