<?php 

namespace Nettools\Core\Helpers\Tests;


use \Nettools\Core\Helpers\SecureRequestHelper;
use \Nettools\Core\Helpers\SecureRequestCookiesInterface;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
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
		return $this->exists;
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
        $this->exists = true;
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
        $this->exists = false;
	}
}





class SecureRequestHelperTest extends TestCase
{
	function testCSRF_Ok()
	{
		$sec = new SecureRequestHelper('csrf', 'form_csrf', 'my_secret', true);
		$stub = new CookiesStub();

		
		$sec->setCookiesInterface($stub);
		$sec->initializeCSRF();

		$cookie = $stub->value;
		
		$this->assertEquals('csrf', $stub->name);
		$this->assertEquals('Strict', $stub->samesite);
		$this->assertEquals($cookie, $sec->getCSRFCookie());
		$this->assertEquals(true, $sec->testCSRFCookie());
		
		JWT::decode($cookie, new Key(md5('my_secret'), 'HS256'));		


		// exception levée si erreur
		$sec->authorizeCSRF(['form_csrf' => $cookie]);	
		
		
		$sec->revokeCSRF();
		$this->assertEquals(NULL, $stub->value);
		$this->assertEquals(NULL, $stub->samesite);
		$this->assertEquals(false, $stub->exists);
	}



	function testCSRF_CookieCorrupt()
	{
		$sec = new SecureRequestHelper('csrf', 'form_csrf', 'my_secret', true);
		$stub = new CookiesStub();

		
		$sec->setCookiesInterface($stub);
		$sec->initializeCSRF();

		$stub->value = 'corruptedvalue';

		// exception levée si erreur
		$this->expectException(\Nettools\Core\Helpers\CSRFException::class);
		$sec->authorizeCSRF(['form_csrf' => 'anything']);	
	}



	function testCSRF_FormTokenCorrupt()
	{
		$sec = new SecureRequestHelper('csrf', 'form_csrf', 'my_secret', true);
		$stub = new CookiesStub();

		
		$sec->setCookiesInterface($stub);
		$sec->initializeCSRF();

		// exception levée si erreur
		$this->expectException(\Nettools\Core\Helpers\CSRFException::class);
		$sec->authorizeCSRF(['form_csrf' => 'dummy']);	
	}



	function testCSRF_NotInitialized()
	{
		$sec = new SecureRequestHelper('csrf', 'form_csrf', 'my_secret', true);
		$stub = new CookiesStub();

		
		$sec->setCookiesInterface($stub);
		$this->assertEquals(false, $sec->getCSRFCookie());

		
		// exception levée si erreur
		$this->expectException(\Nettools\Core\Helpers\CSRFException::class);
		$sec->authorizeCSRF(['form_csrf' => 'anything']);
	}
	
	
	
	function testCSRF_Revoke()
	{
		$sec = new SecureRequestHelper('csrf', 'form_csrf', 'my_secret', true);
		$stub = new CookiesStub();

		
		$sec->setCookiesInterface($stub);
		$sec->initializeCSRF();
		$sec->revokeCSRF();
		
		// exception levée si erreur
		$this->expectException(\Nettools\Core\Helpers\CSRFException::class);
		$sec->authorizeCSRF(['form_csrf' => 'anything']);
	}



}

?>