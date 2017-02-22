<?php

namespace Nettools\Core\ExceptionHandlers;


use \Nettools\Core\Helpers\NetworkingHelper;



/**
 * Extends ExceptionHandler and implements CSS and HTML output of exception
 */
class SimpleExceptionHandler extends ExceptionHandler
{
    /**
     * Get CSS code
     *
     * @return string CSS STYLE tag
     */
    protected function _outputCSS()
    {
        return <<<HTML
<style>

    div#bootstrap_exception_body{
        font-family: Gotham, Helvetica Neue, Helvetica, Arial," sans-serif";
    }

    div#bootstrap_exception_body h1{
        border-bottom: 2px solid firebrick;
        padding-bottom: 5px;
		font-size:2em;
    }

    div#bootstrap_exception_body h2{
        color:#888;
		font-size:1.5em;
    }

    div#bootstrap_exception_body code{
        padding:5px;
        background-color: antiquewhite;
        font-size:0.8em;
        font-family: Consolas, Andale Mono, Lucida Console, Lucida Sans Typewriter, Monaco, Courier New, monospace;
    }

    table#bootstrap_exception{
        font-family: Consolas, Andale Mono, Lucida Console, Lucida Sans Typewriter, Monaco, Courier New, monospace;
        font-size:0.7em;
        border-spacing: 0;
        margin-top:40px;
    }

    table#bootstrap_exception td,
    table#bootstrap_exception th{
        vertical-align: top;
        padding:2px 5px;
        white-space: nowrap;
    }

    table#bootstrap_exception td:nth-child(4){
        white-space: pre;
        display:none;
    }

    table#bootstrap_exception tr:nth-child(2n){
        background-color: #ccc;
    }
</style>
HTML;

    }
    
    
    /**
     * Get a string with exception properly formatted
     * 
     * @param \Throwable $e Exception object
     * @param string $h1 The title displayed on the error page
     * @return string Returns a string with $e exception object properly formatted to be human-readable
     */
    protected function _getException(\Throwable $e, $h1 = 'An error occured')
    {
        // exception class
        $kind = get_class($e);
        
        
        $path_to_root = $_SERVER['DOCUMENT_ROOT'];
        
        
        // output CSS
        $ret = $this->_outputCSS();
        
        
        // output JS / raw HTML
        $ret .= <<<HTML
<script>
    function funParameters()
    {
        var tds = document.querySelectorAll('table#bootstrap_exception td:nth-child(4)');
        var tdsl = tds.length;
        for ( var i = 0 ; i < tdsl ; i++ )
            tds[i].style.display = "block";
    }
</script>
<div id="bootstrap_exception_body">
<h1>$h1</h1>
<h2>$kind</h2>
<code>{$e->getMessage()}</code>

<table id="bootstrap_exception">
    <tr>
        <th>File</th>
        <th>Line</th>
        <th>Function</th>
        <th>Parameters <a href="javascript:void(0)" onclick="funParameters(); return false;">Display parameters</a></th>
    </tr>
HTML;


        // handle stack trace
        $ret .= "<tr><td>" . str_replace($path_to_root . '/', '', $e->getFile()) . "</td><td>" . $e->getLine() . "</td><td>throw new " . get_class($e) . " </td><td></td></tr>\n";
        foreach ( $e->getTrace() as $trace )
        {
            $file = str_replace($path_to_root . '/', '', $trace['file']);
            $line = $trace['line'];

            if ( $trace['class'] )
                $function = $trace['class'] . '::' . $trace['function'];
            else
                $function = $trace['function'];

            $args = htmlspecialchars(print_r($trace['args'], true));

            $ret .= "<tr><td>$file</td><td>$line</td><td>$function</td><td>$args</td></tr>\n";
        }

        $ret .= "</table></div>";
        return $ret;   
    }

	
}

?>