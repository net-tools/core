<?php 

namespace Nettools\Core\Helpers\Tests;


use \Nettools\Core\Helpers\SecureRequestHelper;
use \PHPUnit\Framework\TestCase;




/** 
 * Class to interface with browser cookies (unit test)
 */
class CookiesStub implements SecureRequestCookiesInterface {

	
	public $name;
	public $value;
	public $samesite;
	public $exists;
	
	
	
	/**
	 * Get cookie value
	 *
	 * @param string $name
	 * @return string|false Returns cookie or FALSE if cookie not found
	 */
	public function getCookie($name)
	{
		$this->name = $name;
		return $this->value;
	}
	
	
	
	/**
	 * Test if cookie exists
	 *
	 * @param string $name
	 * @return bool
	 */
	public function testCookie($name)
	{
		$this->name = $name;
		return $exists;
	}
	
	
	
	/**
	 * Set cookie value
	 *
	 * @param string $name Cookie name
	 * @param string $value Cookie value
	 * @param string $samesite Cookie 'samesite' attribute (may be set to None, Lax, Strict)
	 */
	public function setCookie($name, $value, $samesite = 'Lax')
	{
		$this->name = $name;
		$this->value = $value;
		$this->samesite = $samesite;
	}
	
	
	
	/**
	 * Delete cookie
	 *
	 * @param string $name Cookie name
	 */
	public function deleteCookie($name)
	{
		$this->value = NULL;
		$this->samesite = NULL;
	}
}





class SecureRequestHelperTest extends TestCase
{
	function testCSRF_Ok()
	{
		$sec = new SecureRequestHelper('csrf', 'form_csrf', 'my_secret', true);
		$stub = new CoookiesStub();

		
		$sec->setCookiesInterface($s);
		$sec->initializeCSRF();

		$cookie = $s->value;
		
		$this->assertEquals('csrf', $s->name);
		$this->assertEquals('Strict', $s->samesite);
		$this->assertEquals($cookie, $sec->getCSRFCookie());
		$this->assertEquals(true, $sec->testCookie());
		
		JWT::decode($cookie, new Key(md5('my_secret'), 'HS256'));		


		// exception levée si erreur
		$sec->authorizeCSRF(['form_csrf' => $cookie]);	
		
		
		$this->assertEquals(true, $sec->revokeCSRF());
		$this->assertEquals(NULL, $s->value);
		$this->assertEquals(NULL, $s->samesite);
	}
}

?>