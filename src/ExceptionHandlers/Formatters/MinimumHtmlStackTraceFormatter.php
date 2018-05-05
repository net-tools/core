<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;





/**
 * Class to get the stack trace as HTML, with no parameters column
 */
class MinimumHtmlStackTraceFormatter extends HtmlStackTraceFormatter
{
    /**
     * Format a stack trace as a string with suitable format
	 *
     * @param \Nettools\Core\ExceptionHandlers\Res\StackTrace $stack
     * @return string
     */
	public function format(StackTrace $stack)
	{
		$ret = $css . "<table class=\"nettools_core_exceptionhandlers_exception\"><tr><th>File</th><th>Line</th><th>Function</th></tr>";
		
		foreach ( $stack->stack as $item )
			$ret .= '<tr><td>' . implode('</td><td>', array_slice($item, 0, 3)) . '</td></tr>';
		
		$ret .= "</table>";
		return $ret;
	}

}

?>