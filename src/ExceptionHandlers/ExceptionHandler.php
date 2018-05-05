<?php

namespace Nettools\Core\ExceptionHandlers;


use \Nettools\Core\Helpers\NetworkingHelper;



/**
 * Abstract class to format exception so that error lines could be displayed on screen and easily read
 */
abstract class ExceptionHandler
{
    /**
     * Get a strategy object of class Formatter that will handle conversion of exception body + stack trace to a string
	 *
	 * @return Formatters\Formatter
     */
    abstract protected function _getFormatterStrategy();	
	
	
	
	/**
	 * Get exception stack trace as a string 
	 *
     * @param \Throwable $e Exception to handle
     * @param string $h1 The title displayed on the error page
	 * @return string Exception stack trace as a string
	 */
	protected function _getException(\Throwable $e, $h1)
	{
		try
		{
			return $this->_getFormatterStrategy()->format($e, $h1);
		}
		catch (\Throwable $e2)
		{
			return "Error '" . get_class($e2) . "' during processing of exception '" . get_class($e) . "' with message '{$e2->getMessage()}'.";
		}
	}
	
    
	
	/**
     * Get a link for mobile display (will be hidden if display is larger than 800px)
     * 
     * @param string $exstr Exception formatted as a string
     * @return string Returns the string $exstr with an appended line to display a popup window
     */
	protected function _mobileLink($exstr)
	{
		// écriture affichage lien pour version mobile ; caché pour version normal et grand écran
		$exattr = '<!DOCTYPE html><html><body>' . htmlspecialchars($exstr) . '</body></html>';
		
		$autohide = <<<AUTOHIDE

/* version non mobile : masquer */
@media screen and (min-width:800px) {
	.mobile_link_exception {
		display:none;
		visibility:hidden;
	}
}

AUTOHIDE;
        
        
        $mobilelink = <<<LINK

<style>
.mobile_link_exception{
	outline:none; 
	background-color:antiquewhite; 
    font-family: Gotham, Helvetica Neue, Helvetica, Arial," sans-serif";
	color:black; 
	font-size:12px; 
	font-weight:bold; 
	padding:3px; 
	margin-top:10px; 
	margin-bottom:10px; 
	display:block; 
	border-top:2px solid firebrick;
	border-bottom:2px solid firebrick;
}

$autohide

</style>

<script>
function openPage(a)
{
	var exw = window.open('', "ExceptionWindow" + (Math.floor(Math.random() * 10000000)));
	if ( exw )
	{
		exw.document.write(a.getAttribute('data-exception'));
		exw.document.close();
	}
	else
		alert('Cannot open a popup window');
}
</script>



<a href="javascript:void(0)" onclick="openPage(this); return false;" class="mobile_link_exception" data-exception="{$exattr}">Open a new page and display error data</a>

LINK;
		
		return $mobilelink;
	}
    
        
    
    /**
     * Handle an exception during a GET/POST request and display in browser output a human-readable exception data
     *
     * The formatted exception is outputed directly to standard output.
     *
     * @param \Throwable $e Exception to format
     */
    protected function _handleGETPOSTException(\Throwable $e)
    {
        header("Content-Type: text/html; charset=utf-8");

		// get exception string (stack trace)
		$ex = $this->_getException($e, 'Error during request');
		
        echo '<!DOCTYPE html><html><body>';

		// reset css and adapt font-size according to display width
        echo <<<HTML
<style>

	body{
		font-size:1em;		
	}
	
	
	/* width>1400 : larger font */
	@media screen and (min-width:1400px) {
		body {
			font-size:1.1em;
		}
	}

	
	/* width < 800 : mobile screen, smaller font */
	@media screen and (max-width:800px) {
		body {
			font-size:0.6em;
		}
	}
</style>

HTML;

		echo $ex;
		
		// mobile link
		echo $this->_mobileLink($ex);
		echo '</body></html>';
    }
    
    

    /** 
     * Handle an exception during an XMLHTTP request.
     *
     * The XMLHTTP request JSON response will contain a 'exception' property with the exception data formatted as a human-readable string.
     * The JSON formatted exception is outputed directly to standard output.
     *
     * @param \Throwable $e Exception
     */
    protected function _handleXMLHTTPCommandException(\Throwable $e)
    {
		// send xmlhttp headers
		header("Content-Type: application/json; charset=utf-8");
		
		// no cache
		header("Expires: Sat, 1 Jan 2005 00:00:00 GMT");
		header("Last-Modified: ".gmdate( "D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache"); 
        
        
		// get exception string (stack trace)
        $ex = $this->_getException($e, 'Error during async request');
        
        echo json_encode(array('statut'=>false, 'message'=>'Error during async request', 'exception'=>$ex));
    }
    
    
	
	/**
     * Handle an exception
     *
     * @param \Throwable $e Exception to format
     * @return string The exception formatted as a string
     */
	public function handleException(\Throwable $e)
	{
        // headers sent by browser are prefixed with 'HTTP' (so that they don't mess with environment variables), and - are replaced by _
        if ( (strpos($_SERVER['HTTP_USER_AGENT'], 'XMLHTTP') === 0) || (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') === 0) )
            // if XMLHTTP request
            $this->_handleXMLHTTPCommandException($e);

        // if GET/POST request
        else
            $this->_handleGETPOSTException($e);
        
        die();
    }   
}

?>