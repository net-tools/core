<?php


namespace Nettools\Core\Misc;



/**
 * Helper for object litteral compliant with Twig
 *
 * Twig tests that a property exists through reflexion, so the __get magic accessor of AbstractConfig
 * can't work as expected ; we use __call magic accessor, which is used by Twig
 */
class TwigObject {
	
    protected $_obj;
	
	
	
	/** 
	 * Constructor
	 *
	 * @param object $o
	 */
	public function __construct(object $o)
	{
		$this->_obj = $o;
	}
	
	
	
	/**
	 * Get value
	 *
	 * @param string $k 
	 * @return string
	 * @throws \Exception Thrown if value does not exist
	 */
	public function get($k)
	{
		return $this->_obj->{$k};
	}
	
	
	
	/**
	 * Get value inside Twig template, through a method call
	 *
	 * @param string $m Property name
	 * @param array $args 
	 * @return string
	 */
	public function __call($m, $args)
	{
		return $this->get($m);
	}

    
}


?>