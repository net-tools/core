<?php

namespace Nettools\Core\ExceptionHandlers;




/**
 * Extends ExceptionHandler and implements CSS and HTML output of exception
 */
class SimpleExceptionHandler extends ExceptionHandler
{
    /**
     * Get a strategy object of class Formatter that will handle conversion of exception body + stack trace to a string
	 *
	 * @return Formatters\Formatter
     */
    protected function _getFormatterStrategy()
	{
		// create an HTML formatter for exception body, with a stack trace having function parameters hidden by default
		return new Formatters\HtmlFormatter(new Formatters\HiddenParametersHtmlStackTraceFormatter());
	}
}

?>