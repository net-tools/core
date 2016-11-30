<?php

// namespace
namespace Nettools\Core\Formatters;




// output strategy to a string
class FormatterFileOutputStrategy extends FormatterOutputStrategy
{
	protected $_data = NULL;
	
	
	// constructor
	public function __construct()
	{
		$this->_data = '';
	}
	
	
	// output data
	public function output($data)
	{
		$this->_data .= $data;
	}
	
	
	// get the output string
	public function getOutput()
	{
		return $this->_data;
	}
}

?>