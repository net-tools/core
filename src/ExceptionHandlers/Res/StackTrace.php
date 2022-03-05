<?php

namespace Nettools\Core\ExceptionHandlers\Res;



/**
 * Helper class to get the stack trace 
 */
class StackTrace
{
	/**
	 * @var array Two-dimensions array for stack ; each row has 4 0-indexed columns : 0=file, 1=line number, 2=function name, 3=function arguments
	 */
	public $stack;
	
	
	
    /**
     * Create stack trace object
	 *
     * 
     * @param \Throwable $e Exception object
     * @return array
     */
	public function __construct(\Throwable $e)
	{
		$this->stack = $this->_get($e);
	}
	
	
	
    /**
     * Get a 2-dimensions array with exception stack trace
	 *
	 * Each row has 4 0-indexed columns : 0=file, 1=line number, 2=function name, 3=function arguments
     * 
     * @param \Throwable $e Exception object
     * @return array
     */
	protected function _get(\Throwable $e)
	{
		$path_to_root = $_SERVER['DOCUMENT_ROOT'];

		$rows = array();
		
		// first row : current script with error line
		$rows[] = [
				// file		
				str_replace($path_to_root . '/', '', $e->getFile()),
			
				// line
				$e->getLine(),
			
				// function
				"throw new " . get_class($e),
			
				// function parameters
				""
			];
		

		
		// for each call stack element
		foreach ( $e->getTrace() as $trace )
		{
			$rows[] = [
					
					// file
					str_replace($path_to_root . '/', '', isset($trace['file']) ? $trace['file'] : ''),
				
					// line
					isset($trace['line']) ? $trace['line'] : '',
				
					// function
					isset($trace['class']) ? ($trace['class'] . '::' . $trace['function']) : $trace['function'],
				
					// args
					print_r(isset($trace['args']) ? $trace['args'] : '', true)
				];
		}
		
		
		return $rows;
	}
}

?>