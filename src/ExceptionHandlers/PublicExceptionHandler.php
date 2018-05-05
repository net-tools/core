<?php

namespace Nettools\Core\ExceptionHandlers;




/**
 * Extends ExceptionHandler and implements quiet output of exception.
 *
 * The stack trace is not outputted on screen, but sent to the admin ; intended for public web apps
 */
class PublicExceptionHandler extends ExceptionHandler
{
    /**
     * Get a strategy object of class StackTraceFormatter that will handle conversion of stack trace to a string
	 *
	 * @return Formatters\Formatter
     */
    protected function _getFormatterStrategy()
	{
		return new Formatters\PublicFormatter(new Formatters\NullStackTraceFormatter());
	}
}

?>