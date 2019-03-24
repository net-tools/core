<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;




/**
 * Class to get the exception details in plain text format
 */
class PlainTextFormatter extends Formatter
{
	/**
	 * Output exception body
	 *
     * @param \Throwable $e Exception to format
	 * @param string $h1 Title of error page (such as "an error has occured")
	 * @param string $stackTraceContent
	 * @return string
	 */
	protected function body(\Throwable $e, $h1, $stackTraceContent)
	{
        // exception class
        $kind = get_class($e);
		
		// get body
		return "** $h1 **\r\n" .
				"Exception: $kind\r\n" .
			    "Code: {$e->getMessage()}\r\n" .
				"\r\n" .
				$stackTraceContent;
	}
}

?>