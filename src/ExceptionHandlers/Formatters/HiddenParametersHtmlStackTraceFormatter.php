<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;





/**
 * Class to get the stack trace as HTML, with parameters hidden by default
 */
class HiddenParametersHtmlStackTraceFormatter extends HtmlStackTraceFormatter
{
    /**
     * Format a stack trace as a string with suitable format
	 *
     * @param \Nettools\Core\ExceptionHandlers\Res\StackTrace $stack
     * @return string
     */
	public function format(StackTrace $stack)
	{
		// get CSS
		$ret = $this->_getCSS();
		
		// update CSS and JS for hidden parameters by default
		$ret .= <<<HTML
		
<style>
table.nettools_core_exceptionhandlers_exception td:nth-child(4){
	display:none;
}
</style>

<script>
function _unhideParameters()
{
	var tds = document.querySelectorAll('table.nettools_core_exceptionhandlers_exception td:nth-child(4)');
	var tdsl = tds.length;
	for ( var i = 0 ; i < tdsl ; i++ )
		if ( tds[i].style.display == 'none' )
			tds[i].style.display = "block";
		else
			tds[i].style.display = "none";
}
</script>

HTML;
		
		
		$ret .= "<table class=\"nettools_core_exceptionhandlers_exception\"><tr><th>File</th><th>Line</th><th>Function</th>" .
				"<th>Paramètres <a href=\"javascript:void(0)\" onclick=\"_unhideParameters(); return false;\">(unhide)</a></th></tr>";
		
		foreach ( $stack->stack as $item )
			$ret .= '<tr><td>' . implode('</td><td>', $item) . '</td></tr>';
		
		$ret .= "</table>";
		return $ret;
	}

}

?>