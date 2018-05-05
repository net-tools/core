<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;



/**
 * Class to get the stack trace as HTML
 */
class HtmlStackTraceFormatter extends StackTraceFormatter
{
	/**
	 * Get CSS string 
	 *
	 * @return string
	 */
	protected function _getCSS()
	{
		return <<<CSS
<style>
table.nettools_core_exceptionhandlers_exception{
	font-family: Consolas, Andale Mono, Lucida Console, Lucida Sans Typewriter, Monaco, Courier New, monospace;
	font-size:0.7em;
	border-spacing: 0;
	margin-top:40px;
}

table.nettools_core_exceptionhandlers_exception td,
table.nettools_core_exceptionhandlers_exception th{
	vertical-align: top;
	padding:2px 5px;
	white-space: nowrap;
}

table.nettools_core_exceptionhandlers_exception td:nth-child(4){
	white-space: pre;
}

table.nettools_core_exceptionhandlers_exception tr:nth-child(2n){
	background-color: #ccc;
}
</style>

CSS;
		
	}
	
	
	
    /**
     * Format a stack trace as a string with suitable format
	 *
     * @param \Nettools\Core\ExceptionHandlers\Res\StackTrace $stack
     * @return string
     */
	public function format(StackTrace $stack)
	{
		$ret = $this->_getCSS() . "<table class=\"nettools_core_exceptionhandlers_exception\"><tr><th>File</th><th>Line</th><th>Function</th><th>Parameters</th></tr>";
		
		foreach ( $stack->stack as $item )
			$ret .= '<tr><td>' . implode('</td><td>', $item) . '</td></tr>';
		
		$ret .= "</table>";
		return $ret;
	}

}

?>