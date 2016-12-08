<?php
/**
 * FormatterStringOutputStrategy
 *
 * @author Pierre - dev@net-tools.ovh
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
	
	
	/**
     * Output data to the string
     * 
     * @param string $data Data to concatenate to the string being built
     */
	public function output($data)
	{
		$this->_data .= $data;
	}
	
	
	/**
     * Get the string built
     * 
     * @return string The string built with calls to `FormatterFileOutputStrategy::output`
     */
	function getOutput()
	{
		return $this->_data;
	}
}

?>