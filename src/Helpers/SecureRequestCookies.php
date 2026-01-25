<?php
/**
 * SecureRequestHelper
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Helpers;






/** 
 * Class to interface with browser cookies
 */
class SecureRequestCookies implements SecureRequestCookiesInterface {

	
	/**
	 * Get cookie value
	 *
	 * @param string $name
	 * @return string|false Returns cookie or FALSE if cookie not found
	 */
	 public function getCookie($name)
	 {
		 if ( $this->testCookie($name) )
			 return $_COOKIE[$name];
		 else
			 return false;
	 }
	
	
	
	/**
	 * Test if cookie exists
	 *
	 * @param string $name
	 * @return bool
	 */
	public function testCookie($name)
	{
		return array_key_exists($name, $_COOKIE);
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
		setcookie($name, $value, 
				[ 
					'expires'	=> 0, 
					'secure'	=> true,
					'domain'	=> null,
					'path'		=> '/',
					'samesite'	=> $samesite
				]
			);
		$_COOKIE[$name] = $value;
	}
	
	
	
	/**
	 * Delete cookie
	 *
	 * @param string $name Cookie name
	 */
	public function deleteCookie($name)
	{
		setcookie($name, '', time() - 3600, '/');
		unset($_COOKIE[$name]);
	}
}

?>