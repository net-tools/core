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
    /** write header */
	function header(){}

    /** write footer */
    function footer(){}


    /** begin a new row */
	function beginRow(){}

    /** end the row */
	function endRow(){}

    /** get column separator */
    function rowSeparator(){ return "\r\n"; }

    /** begin new column */
    function beginColumn(){}

    /** end column */
	function endColumn(){}

    /** get column separator */
    function columnSeparator(){ return ";"; }
}

?>