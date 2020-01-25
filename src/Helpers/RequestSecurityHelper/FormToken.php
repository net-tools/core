<?php

// namespace
namespace Nettools\Core\Helpers\RequestSecurityHelper;




/**
 * Helper class for form token
 */
class FormToken
{
	protected $_intf;
	
	
	
	/** 
	 * Constructeur
	 *
	 * @param ClientInterface $intf Client interface
	 */
	public function __construct(ClientInterface $intf)
	{
		$this->_intf = $intf;
	}
	
	
	
	/** 
	 * Compute a form token
	 *
	 * @param string $cnametoken Token name
	 * @param string $c Valeur du jeton Token value
	 * @param string $secret Secret as a string
	 * @return string Returns a string ; 64 last digits contain a hash of token value $c
	 */
	protected function compute($cnametoken, $c, $secret)
	{
		return $cnametoken . hash_hmac('sha256', 'nonce:' . $c, $secret);
	}
	
	
	
	/** 
	 * Create a form token
	 *
	 * @return string Returns a string with 32+64 characters ; token is also sent to the client as a cookie (double-submit pattern)
	 */
	public function create()
	{
		$c = bin2hex(random_bytes(32));						
		$cnametoken = md5('cookie-name!' . time());			// cookie name ; can be seen in the first 32 digits of string token
		$cname = md5('md5-cookie-name!' . $cnametoken);		// real cookie name ; this is the cookie key set in the browser
		
		// stores the value in the client
		$this->_intf->set($cname, $c);
		
		// returns computed token
		return $this->compute($cnametoken, $c, basename(__FILE__));
	}
	
	
	
	/**
	 * Check the form token
	 *
	 * @param string $t Token to check
	 * @return bool
	 */
	public function check($t)
	{
		if ( is_null($t) )
			$t = '';	

		
		// first 32 digits : cookie name as seen in the token string
		$cnametoken = substr($t, 0, 32);
		// real cookie name in the client
		$cname = md5('md5-cookie-name!' . $cnametoken);

		// get cookie value from client, compute again the token with same parameters and compare with token $t
		$c = $this->_intf->get($cname);
		$b = hash_equals($this->compute($cnametoken, $c, basename(__FILE__)), $t);
		
		// deleting form token in client
		$this->_intf->delete($cname);
		return $b;
	}
	
}