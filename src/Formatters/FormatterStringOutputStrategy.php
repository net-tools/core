<?php
/**
 * FormatterStringOutputStrategy
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Formatters;




/**
* Output strategy to a string
*/
class FormatterStringOutputStrategy extends FormatterOutputStrategy
{
    /**
    * @var string String being built
    */
    protected $_data = NULL;
	
	
	/**
     * Constructor of the string output strategy
     */
	public function __construct()
	{
		$this->_data = '';
	}
	
	
	public function output($data)
	{
		$this->_data .= $data;
	}
	
	
	/**
     * Get the string built
     * 
     * @return string The string built with calls to `FormatterFileOutputStrategy::output`
     */
	public function getOutput()
	{
		return $this->_data;
	}
}

?>