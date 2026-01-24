<?php
/**
 * SecureRequestHelper
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Helpers;



use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;






/** 
 * Helper cass to deal with secure requests
 */
class SecureRequestHelper {

	
	protected $_csrf_cookiename;
	protected $_csrf_submittedvaluename;
	protected $_secret;
	
	
	
	/** 
	 * Constructor 
	 *
	 * @param string $csrf_cookiename Name of CSRF cookie
	 * @param string $csrf_submittedvaluename Name of CSRF value submitted along the request (double CSRF cookie submit pattern)
	 */
	public function __construct($csrf_cookiename = '_CSRF_', $csrf_submittedvaluename = '_FORM_CSRF_', $secret = __FILE__)
	{
		$this->_csrf_cookiename = $csrf_cookiename;
		$this->_csrf_submittedvaluename = $csrf_submittedvaluename;
		$this->_secret = $secret;
	}
	
	
	
	/**
	 * Get CSRF cookie name
	 *
	 * @return string
	 */
	public function getCSRFCookieName()
	{
		return $this->_csrf_cookiename;
	}
	
	
	
	/**
	 * Get CSRF cookie value
	 * 
	 * @return string
	 * @throws CSRFException Thrown if the CSRF layer has not been initialized
	 */
	public function getCSRFCookie()
	{
		$cookie = array_key_exists($this->_csrf_cookiename, $_COOKIE) ? $_COOKIE[$this->_csrf_cookiename] : null;
		if ( !$cookie )
			throw new CSRFException('CSRF cookie has not been initialized');
		
		return $cookie;
	}
	
	
	
	/**
	 * Test if CSRF cookie exists
	 * 
	 * @return bool
	 */
	public function testCSRFCookie()
	{
		$cookie = array_key_exists($this->_csrf_cookiename, $_COOKIE) ? $_COOKIE[$this->_csrf_cookiename] : null;
		return $cookie ? true : false;
	}
	
	
	
	/**
	 * Get CSRF submitted value name
	 *
	 * @return string
	 */
	public function getCSRFSubmittedValueName()
	{
		return $this->_csrf_submittedvaluename;
	}
	
	
	
	/** 
	 * Initialize security layer (sends a CSRF cookie to the browser)
	 */
	public function initializeCSRF()
	{
		// prepare JWT stuff ; the JWT token will expire in 1 day
		$opt = [ 'tok' => bin2hex(random_bytes(32)), 'csrf' => $this->_csrf_cookiename, 'exp' => time()+60*60*24 ];
		$value = JWT::encode($opt, md5($this->_secret), 'HS256');
				
		// set cookie in browser
		setcookie($this->_csrf_cookiename, $value, 0, '/');
		$_COOKIE[$this->_csrf_cookiename] = $value;
	}
	
	
	
	/**
	 * Revoke CSRF cookie
	 */
	public function revokeCSRF()
	{
		setcookie($this->_csrf_cookiename, '', time() - 3600, '/');
		unset($_COOKIE[$this->_csrf_cookiename]);
	}
	
	
	
	/** 
	 * Authorize a request with CSRF security (double-submitted CSRF cookie pattern)
	 * 
	 * @param string[] $request
	 * @return bool Returns TRUE if request is authorized
	 * @throws CSRFException Thrown if the request has not been authorized
	 */
	public function authorizeCSRF(array $request)
	{
		$token = array_key_exists($this->_csrf_submittedvaluename, $request) ? $request[$this->_csrf_submittedvaluename] : null;
		if ( is_null($token) )
			throw new CSRFException('CSRF security validation failed');
		
		
		try
		{
			// check JWT
			$payload = JWT::decode($token, new Key(md5($this->_secret), 'HS256'));
		}
		catch ( \Exception $e )
		{
			throw new CSRFException('CSRF security validation failed');
		}


		// checking payload
		if ( ($payload->csrf != $this->_csrf_cookiename) || !hash_equals($this->getCSRFCookie(), $payload->tok) )
			throw new CSRFException('CSRF security validation failed');

		return true;
	}
	
	
	
	/**
	 * Get the HTML for an hidden CSRF field
	 *
	 * @return string
	 */
	public function addCSRFHiddenInput()
	{
		return "<input type=\"hidden\" name=\"{$this->_csrf_submittedvaluename}\" value=\"{$this->getCSRFCookie()}\">";
	}
}

?>