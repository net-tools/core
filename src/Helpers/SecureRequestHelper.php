<?php
/**
 * SecureRequestHelper
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



// namespace
namespace Nettools\Core\Helpers;




/** 
 * Helper cass to deal with secure requests
 */
class SecureRequestHelper {

	
	protected $_csrf_cookiename;
	protected $_csrf_submittedvaluename;
	
	
	
	/** 
	 * Constructor 
	 *
	 * @param string $csrf_cookiename Name of CSRF cookie
	 * @param string $csrf_submittedvaluename Name of CSRF value submitted along the request (double CSRF cookie submit pattern)
	 */
	public function __construct($csrf_cookiename = '_CSRF_', $csrf_submittedvaluename = '_FORM_CSRF_')
	{
		$this->_csrf_cookiename = $csrf_cookiename;
		$this->_csrf_submittedvaluename = $csrf_submittedvaluename;
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
	 */
	public function getCSRFCookie()
	{
		return $_COOKIE[$this->_csrf_cookiename];
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
	public function initialize()
	{
		// create a CSRF value
		$value = bin2hex(random_bytes(32));
		setcookie($this->_csrf_cookiename, $value, 0, '/');
		$_COOKIE[$this->_csrf_cookiename] = $value;
	}
	
	
	
	/** 
	 * Authorize a request with CSRF security
	 * 
	 * @param string[] $request
	 * @return bool Returns TRUE if request is authorized, false if not (either the CSRF cookie does not exist, or the submitted value does not equal the CSRF cookie)
	 */
	public function authorize(array $request)
	{
		// checking the CSRF cookie exists
		$cookie = $this->getCSRFCookie();
		if ( !$cookie )
			return false;
		
		// if CSRF cookie exists, comparing with double-submitted cookie as a request value
		return hash_equals($cookie, $request[$this->_csrf_submittedvaluename]);		
	}
}

?>