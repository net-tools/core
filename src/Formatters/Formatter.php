<?php
/**
 * Formatter
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Formatters;




/** 
 * Abstract base class for exporting data (to CSV, HTML, etc.)
 */
abstract class Formatter
{
	/**
     * @var FormatterOutputStrategy Strategy pattern to delegate output (to a string or file, for example)
     */
	protected $_strategy = NULL;
	
	
	/** Abstract method for header output */
	abstract function header();

	/** Abstract method for footer output */
    abstract function footer();

	/** Abstract method for beginning a new row */
	abstract function beginRow();

	/** Abstract method for ending a row */
    abstract function endRow();

	/** Abstract method to get the row separator characters */
    abstract function rowSeparator();

	/** Abstract method for beginning a new column */
    abstract function beginColumn();
	
	/** Abstract method for beginning a column */
    abstract function endColumn();

	/** Abstract method to get the column separator characters */
    abstract function columnSeparator();
	
	
	/**
     * Constructor of the Formatter
     * 
     * @param FormatterOutputStrategy $outputStrategy Provide here a formater output strategy (to file or string, for example)
     */
	public function __construct(FormatterOutputStrategy $outputStrategy)
	{
		$this->_strategy = $outputStrategy;
	}
	
	
	/**
     * Get current output strategy
     * 
     * @return FormatterOutputStrategy Returns the current output strategy object
     */
	public function getOutputStrategy()
	{
		return $this->_strategy;
	}
	
	
	/** 
     * Create a new row
     */
	public function newRow()
	{
		$this->_strategy->output($this->beginRow());
	}
	
	
	/**
     * End the row 
     * 
     * @param bool $last Indicates whether this is the last row or not
     */
	public function closeRow($last = false)
	{
		$this->_strategy->output($this->endRow() . ($last ? '' : $this->rowSeparator()));
	}
	
	
	/**
     * Write a column value for the current row 
     * 
     * @param string $v Value to output
     * @param bool $last Indicates whether this is the last column of the row or not
     */
	public function column($v, $last = false)
	{
		$this->_strategy->output($this->beginColumn() . $v . $this->endColumn() . ($last ? '' : $this->columnSeparator()));
	}
	
	
	/**
     * Write a row
     * 
     * @param string[] $r Array of columns values to output
     * @param bool $last Indicates whether this is the last row or not
     */

	public function row($r, $last = false)
	{
		$this->_strategy->output($this->beginRow());
		
		$values = array_values($r);
		$valuesl = count($values);
		
		for ( $i = 0 ; $i < $valuesl ; $i++ )
			$this->column($values[$i], $i+1 == $valuesl);
		
		$this->_strategy->output($this->endRow() . ($last ? '' : $this->rowSeparator()));
	}
}

?>