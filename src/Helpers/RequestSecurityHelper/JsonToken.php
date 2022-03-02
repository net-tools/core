<?php

// namespace
namespace Nettools\Core\Helpers\RequestSecurityHelper;




/**
 * Class for token as a JSON string
 */
class JsonToken
{
	/** 
	 * @var object Token as an object
	 */
	protected $_token;
	
	
	
	/** 
	 * Constructor
	 *
	 * @param object|array Token as an object or associative array
	 */
	public function __construct($init)
	{
		$this->_token = is_array($init) ? (object)$init : $init;
	}
	
	
	
	/** 
	 * Create the json token from a json-formatted string
	 *
	 * @param string $json Json-formatted string
	 * @return JsonToken Return a JsonToken object
	 */
	public static function fromJson($json)
	{
		$js = json_decode($json);
		if ( is_null($js) )
			return null;
		else
			return new JsonToken($js);
	}
	
	
	
	/**
	 * Converting object to a json-formatted string
	 *
	 * @return string 
	 */
	public function toJson()
	{
		return json_encode($this->_token);
	}
	
	
	
	/**
	 * Magic method invoked to convert object to a string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}
	
	
	
	/**
	 * Magic accessor to read value
	 * 
	 * @param string $k Value name
	 * @return mixed 
	 */
	public function __get($k)
	{
		return property_exists($this->_token, $k) ? $this->_token->{$k} : null;
	}
	
	
	
	/**
	 * Magic accessor to write value
	 * 
	 * @param string $k Value name
	 * @param mixed $v 
	 */
	public function __set($k, $v)
	{
		return $this->_token->{$k} = $v;
	}
	
	
	
}