<?php

// namespace
namespace Nettools\Core\Helpers;


use \Nettools\Core\Helpers\RequestSecurityHelper\FormToken;
use \Nettools\Core\Helpers\RequestSecurityHelper\BrowserClient;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;




/**
 * helper class to deal with tokens in requests
 */
final class RequestSecurityHelper
{
	private static $_formTokenObject;
	private static $_timestampTokenObject;
	
	
	
	/**
	 * Create a FormToken object (singleton pattern)
	 *
	 * @return FormToken
	 */
	private static function _getFormTokenObject()
	{
		if ( !self::$_formTokenObject )
			self::$_formTokenObject = new FormToken(new BrowserClient());
		
		
		return self::$_formTokenObject;
	}
	
	
	
	/** 
	 * Create a form token (double-submit value pattern)
	 *
	 * @return string Returns a string of length 32+64 digits ; a cookie is sent back to the browser
	 */
	static function formToken()
	{
		return self::_getFormTokenObject()->create();
	}
	
	
	
	/**
	 * Check a form token
	 *
	 * @param string $t Token to check
	 * @return bool
	 */
	static function checkFormToken($t)
	{
		return self::_getFormTokenObject()->check($t);
	}
	
	
		
	
	/**
    * Create a token with an expiration time
    * 
    * By default, the token expires 60 seconds later ; to create the token, you may provide the validity delay as seconds from now
    * 
    * @param int $delay Number of seconds of the validity delay, counting from now
    * @param string $secret A secret to use to generate the token
	* @param string[] $payload Custom payload of token as an associative array
    * @return string A timestamp token with an embedded expiration time
    */
	static function createTimestampToken($delay = 60, $secret = 'token', $payload = [])
	{
		$payload2 = array(["exp" => time() + $delay]);
		return JWT::encode(array_merge($payload, $payload2), $secret, 'HS256');
	}
	
	
	
	/**
     * Check a timestamp token
     * 
     * @param string $token The token to check (valid, not altered, not expired)
     * @param string $secret The secret used to generate the token
	 * @param string[] $payload If present, this will be set with the token payload as an assocative array
     * @return bool If altered OR expired, returning FALSE
     */
	static function checkTimestampToken($token, $secret = 'token', ?array &$payload = NULL)
	{
		try
		{
			$dummy = JWT::decode($token, new Key($secret, 'HS256'));
			$payload = (array)$dummy;
			return true;
		}
		catch ( \Exception $e )
		{
			return false;
		}
	}
	
}