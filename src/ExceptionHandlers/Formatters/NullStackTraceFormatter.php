<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;





/**
 * Dummy class to ignore the stack trace output
 */
class NullStackTraceFormatter extends StackTraceFormatter
{
    /**
     * Format a stack trace as a string with suitable format
	 *
     * @param \Nettools\Core\ExceptionHandlers\Res\StackTrace $stack
     * @return string
     */
	public function format(StackTrace $stack)
	{
		return '';
	}

}

?>