<?php
/**
 * BrowserInterface
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */



// namespace
namespace Nettools\Core\Helpers\SecureRequestHelper;




/** 
 * Helper class to deal with browser interactions, such as cookies or session
 */
class BrowserInterface extends AbstractBrowserInterface {
	
	/**
	 * Set a cookie
	 *
	 * @param string $name
	 * @param string $value
	 * @param int $expires
	 * @param string $domain
	 */
	public function setCookie($name, $value, $expires, $domain)
	{
		setcookie($name, $value, $expires, $domain);
		$_COOKIE[$name] = $value;
	}

	
	
	/**
	 * Delete a cookie
	 *
	 * @param string $name
	 * @param string $domain
	 */
	public function deleteCookie($name, $domain)
	{
		setcookie($name, '', time() - 3600, $domain);
		unset($_COOKIE[$name]);
	}

	
	
	/**
	 * Get a cookie
	 *
	 * @param string $name
	 * @return string
	 */
	public function getCookie($name)
	{
		return array_key_exists($name, $_COOKIE) ? $_COOKIE[$name] : null;
	}
	
}

?>