<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;



/**
 * Class to get the exception details in HTML format
 */
class HtmlFormatter extends Formatter
{
	/**
	 * Get CSS string 
	 *
	 * @return string
	 */
	protected function _getCSS()
	{
		return <<<CSS
<style>
div.nettools_core_exceptionhandlers_exception_body{
	font-family: Gotham, Helvetica Neue, Helvetica, Arial," sans-serif";
}

div.nettools_core_exceptionhandlers_exception_body h1{
	border-bottom: 2px solid firebrick;
	padding-bottom: 5px;
	font-size:2em;
}

div.nettools_core_exceptionhandlers_exception_body h2{
	color:#888;
	font-size:1.5em;
}

div.nettools_core_exceptionhandlers_exception_body code{
	padding:5px;
	background-color: antiquewhite;
	font-size:0.8em;
	font-family: Consolas, Andale Mono, Lucida Console, Lucida Sans Typewriter, Monaco, Courier New, monospace;
}
</style>

CSS;
		
	}
	
	
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
		return $this->_getCSS() . 
			"<div class=\"nettools_core_exceptionhandlers_exception_body\"><h1>$h1</h1><h2>$kind</h2><code>{$e->getMessage()}</code>" .
			$stackTraceContent .
			"</div>";
	}
}

?>