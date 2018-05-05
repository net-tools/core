<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;





/**
 * Class to get the stack trace as HTML, with first a simple stack trace with no parameters, then a full stack trace
 */
class MinimumAndFullHtmlStackTraceFormatter extends StackTraceFormatter
{
    /**
     * Format a stack trace as a string with suitable format
	 *
     * @param \Nettools\Core\ExceptionHandlers\Res\StackTrace $stack
     * @return string
     */
	public function format(StackTrace $stack)
	{
		return (new MinimumHtmlStackTraceFormatter())->format($stack) .
			"<p>&nbsp;</p><hr><p>&nbsp;</p><h3>Detailed stack trace</h3>" .
			(new HtmlStackTraceFormatter())->format($stack);
	}

}

?>