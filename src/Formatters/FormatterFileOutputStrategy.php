<?php

// namespace
namespace Nettools\Core\Formatters;



// output strategy to a file
class FormatterFileOutputStrategy extends FormatterOutputStrategy
{
	protected $_file = NULL;
	
	
	// constructor with an opened file handle
	public function __construct($filehandler)
	{
		$this->_file = $filehandler;
	}
	
	
	// write to file
	public function output($data)
	{
		fwrite($this->_file, $data);
	}
}

?>