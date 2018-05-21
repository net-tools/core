<?php
/**
 * SecureRequestHelper
 *
 * @author Pierre - dev@nettools.ovh
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
	protected $_csrf_hashsecret;
	protected $_browserInterface;
	
	
	
	/** 
	 * Constructor 
	 *
	 * @param string $csrf_cookiename Name of CSRF cookie
	 * @param string $csrf_submittedvaluename Name of CSRF value submitted along the request (double CSRF cookie submit pattern)
	 * @param string $csrf_hashsecret Secret to use when computing a hashed CSRF submitted value
	 */
	public function __construct($csrf_cookiename = '_CSRF_', $csrf_submittedvaluename = '_FORM_CSRF_', $csrf_hashsecret = '_hash_secret_')
	{
		$this->_csrf_cookiename = $csrf_cookiename;
		$this->_csrf_submittedvaluename = $csrf_submittedvaluename;
		$this->_csrf_hashsecret = $csrf_hashsecret;
		$this->_browserInterface = new SecureRequestHelper\BrowserInterface();
	}
	
	
	
	/** 
	 * Set the browser interface ; used in unit testing
	 *
	 * @param SecureRequestHelper\AbstractBrowserInterface $intf
	 */
	public function setBrowserInterface(SecureRequestHelper\AbstractBrowserInterface $intf)
	{
		$this->_browserInterface = $intf;
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
	 * @throws SecureRequestHelper\CSRFException Thrown if the CSRF layer has not been initialized
	 */
	public function getCSRFCookie()
	{
		$cookie = $this->_browserInterface->getCookie($this->_csrf_cookiename);
		if ( !$cookie )
			throw new SecureRequestHelper\CSRFException('CSRF cookie has not been initialized');
		
		return $cookie;
	}
	
	
	
	/**
	 * Get a hashed CSRF value with a secret (useful to pass the CSRF submitted value as a GET parameter, without disclosing the CSRF cookie value in browser history, cache, etc.)
	 *
	 * The secret must be passed as a constructor parameter.
	 *
	 * @return string Returns the hashed CSRF value prefixed with '!' as a flag
	 */
	public function getHashedCSRFCookie()
	{
		return '!' . hash_hmac('sha256', __METHOD__ . $this->getCSRFCookie(), $this->_csrf_hashsecret);
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
		// create a CSRF value
		$this->_browserInterface->setCookie($this->_csrf_cookiename, bin2hex(random_bytes(32)), 0, '/');
	}
	
	
	
	/**
	 * Revoke CSRF cookie
	 */
	public function revokeCSRF()
	{
		$this->_browserInterface->deleteCookie($this->_csrf_cookiename, '/');
	}
	
	
	
	/** 
	 * Authorize a request with CSRF security (double-submitted CSRF cookie pattern)
	 * 
	 * @param string[] $request
	 * @return bool Returns TRUE if request is authorized
	 * @throws SecureRequestHelper\CSRFException Thrown if the request has not been authorized
	 */
	public function authorizeCSRF(array $request)
	{
		$t = $request[$this->_csrf_submittedvaluename];
		if ( is_null($t) )
			$t = '';
		
		
		// if CSRF submitted value in request begins with '!' it means it has been hashed so that the real CSRF value is not disclosed in GET, history, cache, etc.
		if ( strpos($t, '!') === 0 )
			$b = hash_equals($this->getHashedCSRFCookie(), $t);
		else
			$b = hash_equals($this->getCSRFCookie(), $t);

		
		// if CSRF cookie exists, comparing with double-submitted cookie as a request value
		if ( !$b )
			throw new SecureRequestHelper\CSRFException('CSRF security validation failed');
		
		return true;
	}
	
	
	
	/**
	 * Get the HTML for an hidden CSRF field
	 *
	 * @return string
	 * @throws Exceptions\CSRFException Thrown if the CSRF layer has not been initialized
	 */
	public function addCSRFHiddenInput()
	{
		return "<input type=\"hidden\" name=\"{$this->_csrf_submittedvaluename}\" value=\"{$this->getCSRFCookie()}\">";
	}
}

?>