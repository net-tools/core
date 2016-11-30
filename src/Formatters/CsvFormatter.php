<?php

// namespace
namespace Nettools\Core\Formatters;



// class to deal with CSV data
class CsvFormatter extends Formatter
{
	function header(){}
	function footer(){}


	function beginRow(){}
	function endRow(){}
	function rowSeparator(){ return "\r\n"; }
	function beginColumn(){}
	function endColumn(){}
	function columnSeparator(){ return ";"; }
}

?>