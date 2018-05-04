<?php

namespace Nettools\Core\ExceptionHandlers\StackTraceFormatters;





/**
 * Extends StackTraceFormatter and implements output of exception stack trace through HTML mail and simple onscreen output
 */
class PublicStackTraceFormatter extends HtmlStackTraceFormatter
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		// hide stack trace
		parent::__construct(false);
	}
	
	
	
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
		return "Exception $h1 on " . $_SERVER['HTTP_HOST'];
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
		// get exception details WITH stack trace for emailing
		$html = (new HtmlStackTraceFormatter(true))->format($e, $h1);

		$sep = sha1(uniqid());  
		$headers = ["Content-Type" => "multipart/mixed; boundary=\"$sep\"",
					"From" => $this->getSender()];
		$msg = 	"--$sep\r\nContent-Type: text/plain;\r\n\r\nSee attachment.\r\n\r\n" .
				"--$sep\r\nContent-Type: text/html; name=\"stack-trace.html\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"stack-trace.html\"\r\n\r\n" . trim(chunk_split(base64_encode($html))) . "\r\n\r\n" .
				"--$sep--";
		
		// send mail
		mail($this->getRecipient(), $this->getSubject($e, $h1), $msg, $headers);
		
		
		// get exception string as HTML, with no stack trace
		return parent::format($e, $h1);	
	}
}

?>