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
 * Interface to browser cookies
 */
interface SecureRequestCookiesInterface {

	
	/**
	 * Get cookie value
	 *
	 * @param string $name
	 * @return string
	 */
	public function getCookie($name);
	
	
	
	/**
	 * Test if cookie exists
	 *
	 * @param string $name
	 * @return bool
	 */
	public function testCookie($name);
	
	
	
	/**
	 * Set cookie value
	 *
	 * @param string $name Cookie name
	 * @param string $value Cookie value
	 * @param string $samesite Cookie 'samesite' attribute (may be set to None, Lax, Strict)
	 */
	public function setCookie($name, $value, $samesite = 'Lax');
	
	
	
	/**
	 * Delete cookie
	 *
	 * @param string $name Cookie name
	 */
	public function deleteCookie($name);	
}

?>