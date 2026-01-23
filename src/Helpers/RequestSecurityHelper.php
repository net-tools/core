<?php

// namespace
namespace Nettools\Core\Helpers;


use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;






/**
 * Helper class to deal with tokens in requests
 */
final class RequestSecurityHelper
{
	/** 
	 * Create a form token (double-submit value pattern)
	 *
	 * @param string $tokenName Name of token ; mandatory because a cookie with same name will be sent back to the browser
	 * @param string $secret A secret to hash the token with
	 * @return string Returns a string with token created ; a cookie is sent back to the browser with same token
	 */
	static function formToken($tokenName, $secret)
	{
		$value = bin2hex(random_bytes(32));
		
		setcookie($tokenName, $value);
		$_COOKIE[$tokenName] = $value;
		
		return JWT::encode([ 'tok' => $value, 'tname' => $tokenName ], md5($secret), 'HS256');
	}
	
	
	
	/**
	 * Check a form token
	 *
	 * @param string $token Token to check
	 * @param string $tokenName Name of token (the name of cookie sent back to browser)
	 * @param string $secret A secret to hash the token data with
	 * @return bool
	 */
	static function checkFormToken($token, $tokenName, $secret)
	{
		try
		{
			// decode JWT
			$payload = JWT::decode($token, new Key(md5($secret), 'HS256'));
			
			
			// read cookie
			if ( !array_key_exists($payload->tname, $_COOKIE) )
				return false;
			$cookie = $_COOKIE[$payload->tname];
			
			
			// remove cookie
			setcookie($payload->tname, '', time()-3600);
			unset($_COOKIE[$payload->tname]);
			
			
			// compare 
			return hash_equals($cookie, $payload->tok);
		}
		catch ( \Exception $e )
		{
			return false;
		}
	}
	
	
		
	
	/**
    * Create a token with an expiration time
    * 
    * @param int $delay Number of seconds of the validity delay, counting from now
    * @param string $secret A secret to use to generate the token
	* @param string[] $payload Custom payload of token as an associative array
    * @return string A timestamp token with an embedded expiration time
    */
	static function createTimestampToken($delay, $secret, $payload = [])
	{
		$payload2 = [ "exp" => time() + $delay ];
		return JWT::encode(array_merge($payload, $payload2), md5($secret), 'HS256');
	}
	
	
	
	/**
     * Check a timestamp token
     * 
     * @param string $token The token to check (valid, not altered, not expired)
     * @param string $secret The secret used to generate the token
	 * @param string[] $payload If present, this will be set with the token payload as an assocative array
     * @return bool If altered OR expired, returning FALSE
     */
	static function checkTimestampToken($token, $secret, ?array &$payload = NULL)
	{
		try
		{
			$dummy = JWT::decode($token, new Key(md5($secret), 'HS256'));
			$payload = (array)$dummy;
			return true;
		}
		catch ( \Exception $e )
		{
			return false;
		}
	}
	
}