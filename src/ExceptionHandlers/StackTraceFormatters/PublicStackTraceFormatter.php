<?php

namespace Nettools\Core\ExceptionHandlers\StackTraceFormatters;





/**
 * Extends StackTraceFormatter and implements output of exception stack trace through HTML mail and simple onscreen output
 */
class PublicStackTraceFormatter extends HtmlStackTraceFormatter
{
	/**
	 * Get mail recipient ; by default, send error to postmaster@domain.tld
	 *
	 * @return string
	 */
	protected function getRecipient()
	{
		return \K_NETTOOLS_POSTMASTER;
	}
	
	
	
	/**
	 * Get mail sender ; by default, send error from exception-handler@domain.tld
	 *
	 * @return string
	 */
	protected function getSender()
	{
		return 'exception-handler@' . $_SERVER['HTTP_HOST'];
	}
	
	
	
	/**
	 * Get mail subject
	 *
     * @param \Throwable $e Exception object
     * @param string $h1 The title displayed on the error page
	 * @return string
	 */
	protected function getSubject(\Throwable $e, $h1 = 'An error occured')
	{
		return "Exception $h1 on " . $_SERVER['SCRIPT_URI'];
	}
	
	
	
    /**
     * Get a string with exception stack trace properly formatted
     * 
     * @param \Throwable $e Exception object
     * @param string $h1 The title displayed on the error page
     * @return string Returns a string with $e exception stack trace properly formatted to be human-readable
     */
    public function format(\Throwable $e, $h1 = 'An error occured')
    {
		// get stack trace as HTML
		$html = parent::format($e, $h1);

		// format email
		$msg =	"Content-Type: text/html; charset=UTF-8\r\n" .
				"Content-Transfer-Encoding: quoted-printable\r\n" .
				"\r\n" .
				trim(str_replace("=0A", "\n", str_replace("=0D", "\r", imap_8bit($html))));
		
		// send mail
		mail($this->getRecipient(), $this->getSubject($e, $h1), $msg, "From: " . $this->getSender());
		
		
		// return simple message
		return "<h1>$h1</h1><h2>" . get_class($e) . "</h2>";
    }

	
}

?>