<?php

namespace Nettools\Core\ExceptionHandlers;




/**
 * Extends ExceptionHandler and implements CSS and HTML output of exception
 */
class SimpleExceptionHandler extends ExceptionHandler
{
    /**
     * Get a strategy object of class StackTraceFormatter that will handle conversion of stack trace to a string
	 *
	 * @return StackTraceFormatters\StackTraceFormatter
     */
    protected function _getStackTraceFormatterStrategy()
	{
		return new StackTraceFormatters\HtmlStackTraceFormatter(true);
	}
}

?>