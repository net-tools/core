<?php
/**
 * CsvFormatter
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Formatters;



/**
 * Class to export CSV data 
 * 
 * It inherits from `Formatter` and set the row separator with newlines characters, and the column separator to ";"
 */
class CsvFormatter extends Formatter
{
	function header(){}
    function footer(){}
	function beginRow(){}
	function endRow(){}
    
    /** 
     * Get row separator for CSV
     * 
     * @return string Returns carriage return and newline string
     */ 
    function rowSeparator(){ return "\r\n"; }
    function beginColumn(){}
	function endColumn(){}


    /** 
     * Get column separator for CSV
     * 
     * @return string Returns ';'
     */ 
    function columnSeparator(){ return ";"; }
}

?>