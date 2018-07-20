<?php


namespace Nettools\Core\Misc;



/**
 * Class for HTML resource
 */
class HtmlContent {
	
	
	public $html;
	
	
	/**
	 * Constructor
	 * 
	 * @param string $html
	 */
	public function __construct($html)
	{
		$this->html = $html;
	}
	
	
	
	/**
	 * Convert object to string (magic method)
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->html;
	}
}


?>