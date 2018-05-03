<?php

namespace Nettools\Core\ExceptionHandlers\StackTraceFormatters;



/**
 * Abstract class used as a strategy pattern outside ExceptionHandler to format an exception stack trace (as HTML, or any human-readable [or not] data)
 */
abstract class StackTraceFormatter
{
    /**
     * Get a string with exception stack trace properly formatted
     * 
     * @param \Throwable $e Exception object
     * @param string $h1 The title displayed on the error page
     * @return string Returns a string with $e exception stack trace properly formatted to be human-readable
     */
    abstract public function format(\Throwable $e, $h1 = 'An error occured');	
}

?>