<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;



/**
 * Abstract class to get the exception details in a suitable string format
 */
abstract class Formatter
{
	/**
	 * @var StackTraceFormatter
	 */
	protected $_stackTraceFormatter;
	
	
	
	/**
	 * Constructor
	 *
	 * @param StackTraceFormatter $stFormatter
	 */
	function __construct(StackTraceFormatter $stFormatter)
	{
		$this->_stackTraceFormatter = $stFormatter;	
	}
	
	
	
	/**
	 * Output exception body
	 *
     * @param \Throwable $e Exception to format
	 * @param string $h1 Title of error page (such as "an error has occured")
	 * @param string $stackTraceContent
	 * @return string
	 */
	abstract protected function body(\Throwable $e, $h1, $stackTraceContent);
	
	
	
    /**
     * Format an exception as a string with suitable format
	 *
     * @param \Throwable $e Exception to format
	 * @param string $h1 Title of error page (such as "an error has occured")
     * @return string
     */
	public function format(\Throwable $e, $h1)
	{
		// get body content with stack trace content (stack formatted with strategy passed as constructor parameter)
		return $this->body($e, $h1, $this->_stackTraceFormatter->format(new StackTrace($e)));
	}

}

?>