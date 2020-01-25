<?php

// namespace
namespace Nettools\Core\Helpers\RequestSecurityHelper;




/**
 * Class to interface with client (browser), handling cookies
 */
class BrowserClient implements ClientInterface
{
	/**
	 * Get a value
	 * 
	 * @param string $k Value key
	 * @return string Value with key $k
	 */
	public function get($k)
	{
		return $_COOKIE[$k];
	}
	
	
	
	/**
	 * Sets a value
	 * 
	 * @param string $k Value key
	 * @param string $v Value to set
	 */
	public function set($k, $v)
	{
		setcookie($k, $v);		// set a cookie (expires when browser closes)
		$_COOKIE[$k] = $v;		// cookie available now
	}
	
	
	
	/**
	 * Delete a value
	 *
	 * @param string $k Value key
	 */	
	public function delete($k)
	{
		setcookie($k, '', time()-3600);
		unset($_COOKIE[$k]);
	}
}