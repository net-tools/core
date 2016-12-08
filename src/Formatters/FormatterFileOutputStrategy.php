<?php
/**
 * FormatterFileOutputStrategy
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Formatters;



/**
* Output strategy to a file
*/
class FormatterFileOutputStrategy extends FormatterOutputStrategy
{
    /**
     * @var resource File handle to write to 
     */
    protected $_file = NULL;
	
	
	/**
     * Constructor for the file output strategy
     * 
     * @param resource $filehandler Set this parameter to an opened file handle
     */
	public function __construct($filehandler)
	{
		$this->_file = $filehandler;
	}
	
	
    /**
     * {@inheritDoc}
     */
	public function output($data)
	{
		fwrite($this->_file, $data);
	}
}

?>