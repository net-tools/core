<?php
/**
 * AbstractBrowserInterface
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



// namespace
namespace Nettools\Core\Helpers\SecureRequestHelper;




/** 
 * Abstract class to deal with browser interactions, such as cookies or session
 */
abstract class AbstractBrowserInterface {
	
	/**
	 * Set a cookie
	 *
	 * @param string $name
	 * @param string $value
	 * @param int $expires
	 * @param string $domain
	 */
	abstract public function setCookie($name, $value, $expires, $domain);

	
	
	/**
	 * Delete a cookie
	 *
	 * @param string $name
	 * @param string $domain
	 */
	abstract public function deleteCookie($name, $domain);

	
	
	/**
	 * Get a cookie
	 *
	 * @param string $name
	 * @return string
	 */
	abstract public function getCookie($name);
	
}

?>