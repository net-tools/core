<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;





/**
 * Class to get the stack trace as plain text with column padding, with no parameters column
 */
class MinimumPlainTextStackTraceFormatter extends StackTraceFormatter
{
    /**
     * Format a stack trace as a string with suitable format
	 *
     * @param \Nettools\Core\ExceptionHandlers\Res\StackTrace $stack
     * @return string
     */
	public function format(StackTrace $stack)
	{
		// compute columns max length across all lines of stack trace
		$columnLengths = array(strlen('File'), strlen('Line'), strlen('Function'));
		foreach ( $stack->stack as $item )
			for ( $i = 0 ; $i < count($columnLengths) ; $i++ )
				if ( strlen($item[$i]) > $columnLengths[$i] )
					$columnLengths[$i] = strlen($item[$i]);
		
		// padding titles
		$headers = [];
		foreach ( ['File', 'Line', 'Function'] as $k => $headerColumn )
			$headers[$k] = str_pad($headerColumn, $columnLengths[$k]);
		
		$ret = implode(' | ', $headers) . "\r\n";
		
		// separate header line from stack trace
		$ret .= str_pad('', array_sum($columnLengths) + 3*(count($headers)-1), '-') . "\r\n";
		
		foreach ( $stack->stack as $item )
		{
			$row = [];
			foreach ( array_slice($item, 0, 3) as $col => $value )
				$row[$col] = str_pad($value, $columnLengths[$col]);
			
			$ret .= implode(' | ', $row) . "\r\n";
		}
		
		return "<small>$ret</small>";
	}
}

?>