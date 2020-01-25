<?php

// namespace
namespace Nettools\Core\Helpers\RequestSecurityHelper;




/**
 * Helper class for a token with an expiration delay
 */
class TimestampToken extends ComputedToken
{
	/** 
	 * Compute a token with expiration delay, based on parameters given and a secret
	 *
	 * $params must include 'delay', 'unit', 'unid', and 'ts' parameters for token delay, token delay unit, unique id and timestamp.
	 *
	 * @param object $params Computation parameters
	 * @param string $secret Secret as a string
	 * @return JsonToken Returns computed token as a JsonToken object
	 */
	protected function _compute($params, $secret)
	{
		// if delay < 16, we have to set a 0 as first digit (delay encoded in 2 hex digits)
		$delay = dechex($params->delay);
		if ( strlen($delay) == 1 )
			$delay = "0$delay";
		$unit = $params->unit;
		$unid = $params->unid;
		$ts = $params->ts;
		
		$o = (object)[
			'h'	=> hash_hmac('sha256', "$delay;$unit;$unid;$ts", $secret),
			'd'	=> $delay,
			'u'	=> $unit,
			'i'	=> $unid,
			't'	=> dechex($ts)
		];
		
		
 		return new JsonToken($o);
	}
	
	
	
	/** 
	 * Checks a computed token with a expiration delay
	 *
	 * @param JsonToken $token Token to check
	 * @param string $secret Secret used when building the token
	 * @return bool Returns true if token is valid, false otherwise
	 */
	protected function _check(JsonToken $token, $secret)
	{
		// calc the delay in seconds
		if ( $token->u == 'h' )
			$graceperiod_seconds = hexdec($token->d)*60*60;
		else if ( $token->u == 'm' )
			$graceperiod_seconds = hexdec($token->d)*60;
		else
			$graceperiod_seconds = hexdec($token->d);
			

		// compute token again with public params included in the token
		$ts = hexdec($token->t);
		$params = (object)[
			'delay'	=> hexdec($token->d),
			'unit'	=> $token->u,
			'unid'	=> $token->i,
			'ts'	=> $ts
		];
		$token2 = $this->_compute($params, $secret);
		
		
		// if altered token, second computation above does not give the same hash
		if ( !hash_equals($token2->toJson(), $token->toJson()) )
			return false;
		
		// check delay
		return $ts + $graceperiod_seconds > time();
		
	}
	
	
	
	/** 
	 * Create the token
	 *
	 * @param int $delay Token validity delay (max 255)
	 * @param string $unit Validity delay unit ('s', 'm' or 'h')
	 * @param string $secret Secret as a string
	 * @return JsonToken Returns computed token as a JsonToken object
	 */
	function create($delay = 60, $unit = 's', $secret = 'secret')
	{
		// check that $delay is < 255 ; we are dealing with 1 byte
		if ( $delay > 255 )
			// if $delay > 255 seconds, converting seconds to minutes
			if ( $unit == 's' )
				return $this->create(round($delay / 60), 'm', $secret);
				
            // converting minutes to hours
			else if ( $unit == 'm' )
				return $this->create(round($delay / 60), 'h', $secret);
				
            // converting to 255 hours max
			else
				return $this->create(255, 'h', $secret);

		
		// computation parameters
		$params = (object)[
			'delay'	=> $delay,
			'unit'	=> $unit,
			'unid'	=> bin2hex(random_bytes(32)),
			'ts'	=> time()
		];

		
		return parent::create($params, $secret);
	}
		
	
}