<?php

// namespace
namespace Nettools\Core\Helpers\RequestSecurityHelper;




/**
 * Helper base class for a computed token
 */
abstract class ComputedToken
{
	/** 
	 * Compute token based on parameters given and a secret
	 *
	 * @param object $params Computation parameters
	 * @param string $secret Secret as a string
	 * @return JsonToken Returns computed token as a JsonToken object
	 */
	abstract protected function _compute($params, $secret);
	
	
	
	/** 
	 * Checks a computed token
	 *
	 * @param JsonToken $token Token to check
	 * @param string $secret Secret used when building the token
	 * @return bool Returns true if token is valid, false otherwise
	 */
	abstract protected function _check(JsonToken $token, $secret);
	
	
	
	/** 
	 * Create the token
	 *
	 * @param object $params Computation parameters
	 * @param string $secret Secret as a string
	 * @return JsonToken Returns computed token as a JsonToken object
	 */
	function create($params, $secret = 'secret')
	{
		return $this->_compute($params, $secret);
	}
	
	
	
	/** 
	 * Checks a computed token
	 *
	 * @param JsonToken $token Token to check
	 * @param string $secret Secret used when building the token
	 * @return bool Returns true if token is valid, false otherwise
	 */
	function check(JsonToken $token, $secret = 'secret')
	{
		return $this->_check($token, $secret);
	}
}