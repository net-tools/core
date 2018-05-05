<?php

namespace Nettools\Core\ExceptionHandlers\Formatters;


use \Nettools\Core\ExceptionHandlers\Res\StackTrace;



/**
 * Class to get the exception details in HTML format for public application (don't show stack trace), and
 * send it by email to the webmaster instead
 */
class PublicFormatter extends HtmlFormatter
{
	/**
	 * Get mail recipient ; by default, send error to postmaster@domain.tld
	 *
	 * @return string
	 */
	protected function _getRecipient()
	{
		return \K_NETTOOLS_POSTMASTER;
	}
	
	
	
	/**
	 * Get mail sender ; by default, send error from exception-handler@domain.tld
	 *
	 * @return string
	 */
	protected function _getSender()
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
	protected function _getSubject(\Throwable $e, $h1 = 'An error occured')
	{
		return "Exception $h1 on " . $_SERVER['HTTP_HOST'];
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
		// prepare email body with appropriate formatters : html body + minimum stack first + hr + complete stack
		$html_email = (new HtmlFormatter(new MinimumAndFullHtmlStackTraceFormatter()))->format($e, $h1);
		
		
		// send email
		$sep = sha1(uniqid());  
		$headers = "Content-Type: multipart/mixed; boundary=\"$sep\"\r\n" .
					"From: {$this->_getSender()};";
		$msg = 	"--$sep\r\nContent-Type: text/plain;\r\n\r\nSee attachment.\r\n\r\n" .
				"--$sep\r\nContent-Type: text/html; name=\"stack-trace.html\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"stack-trace.html\"\r\n\r\n" . trim(chunk_split(base64_encode($html_email))) . "\r\n\r\n" .
				"--$sep--";

		mail($this->_getRecipient(), $this->_getSubject($e, $h1), $html_email, $headers);
		
		
		// no stack track for public output
		return parent::body($e, $h1, $stackTraceContent);
	}
}

?>