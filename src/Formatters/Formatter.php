<?php

// namespace
namespace Nettools\Core\Formatters;


use \Nettools\Core\Formatters\FormatterOutputStrategy;



// abstract base class for Formatter
abstract class Formatter
{
	// strategy pattern to delegate output
	protected $_strategy = NULL;
	
	
	// abstract method for header and footer
	abstract function header();
	abstract function footer();


	// base abstract methods : new rows, row separator, column begin/end, column separator
	abstract function beginRow();
	abstract function endRow();
	abstract function rowSeparator();
	abstract function beginColumn();
	abstract function endColumn();
	abstract function columnSeparator();
	
	
	// constructor : must provide a formater output strategy (to file or string, for example)
	public function __construct(FormatterOutputStrategy $outputStrategy)
	{
		$this->_strategy = $outputStrategy;
	}
	
	
	// get current output strategy
	public function getOutputStrategy()
	{
		return $this->_strategy;
	}
	
	
	// new line
	public function newRow()
	{
		$this->_strategy->output($this->beginRow());
	}
	
	
	// end row
	public function closeRow($last = false)
	{
		$this->_strategy->output($this->endRow() . ($last ? '' : $this->rowSeparator()));
	}
	
	
	// write column value
	public function column($v, $last = false)
	{
		$this->_strategy->output($this->beginColumn() . $v . $this->endColumn() . ($last ? '' : $this->columnSeparator()));
	}
	
	
	// write a row ($R is an array of columns to output)
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