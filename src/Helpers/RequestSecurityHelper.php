<?php

// namespace
namespace Nettools\Core\Helpers;


use \Nettools\Core\Helpers\RequestSecurityHelper\FormToken;
use \Nettools\Core\Helpers\RequestSecurityHelper\TimestampToken;
use \Nettools\Core\Helpers\RequestSecurityHelper\JsonToken;
use \Nettools\Core\Helpers\RequestSecurityHelper\BrowserClient;



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
	 * Create a TimestampToken object (singleton pattern)
	 *
	 * @return TimestampToken
	 */
	private static function _getTimestampTokenObject()
	{
		if ( !self::$_timestampTokenObject )
			self::$_timestampTokenObject = new TimestampToken();
		
		
		return self::$_timestampTokenObject;
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
    * Create a token with a expiration time
    * 
    * By default, the token expires 60 seconds later to create the token, you may provide the validity delay,
    * the unit of the delay (seconds, minutes or hours) and a secret. If no parameters, default values will be used
    * 
    * @param int $delay Number of seconds, minutes or hours of the validity delay
    * @param string $unit Provide either 's', 'm' or 'h' to set the unit for the $delay parameter
    * @param string $secret A secret to use to generate the token
    * @return string A timestamp token with a embedded expiration time
    */
	static function createTimestampToken($delay = 60, $unit = "s", $secret = 'token')
	{
		return self::_getTimestampTokenObject()->create($delay, $unit, $secret)->toJson();
	}
	
	
	
	/**
     * Check a timestamp token
     * 
     * @param string $token The token to check (valid, not altered, not expired)
     * @param string $secret The secret used to generate the token
     * @return bool If altered OR expired, returning FALSE
     */
	static function checkTimestampToken($token, $secret = 'token')
	{
		// décoder le jeton
		$token = JsonToken::fromJson($token);
		if ( is_null($token) )
			return false;
		
		return self::_getTimestampTokenObject()->check($token, $secret);
	}
	
}