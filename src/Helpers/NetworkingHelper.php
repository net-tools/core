<?php

// namespace
namespace Nettools\Core\Helpers;




/** 
 * Helper cass to deal with web-specific stuff (json, url, etc.)
 */
class NetworkingHelper {
		
	/** 
     * Output http headers suitable for xmlhttp
     * 
     * @param string $contenttype Content-type to output
     * @param string $charset Charset of content
     */
	static function sendXmlHttpResponseHeaders($contenttype = "application/json", $charset = 'utf-8')
	{
		if ( is_null($charset) )
			$charset = mb_internal_encoding();
			
		// charset
		header("Content-Type: $contenttype; charset=$charset");
		
		// no cache
		header("Expires: Sat, 1 Jan 2005 00:00:00 GMT");
		header("Last-Modified: ".gmdate( "D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");	
	}
	
		
	/**
     * Append a parameter to an url
     * 
     * @param string $url Url string to process
     * @param string $append Querystring to append to $url
     * @return string Return a new url with appended string
     */
	static function appendToUrl($url, $append)
	{
		if ( $append )
		{
			if ( preg_match('|^(.+?)(?:\?(.*?))?(#.*)?$|', $url, $regs) )
			{
				$u = $regs[1];  // URL part
				$q = $regs[2];  // querystring
				$a = $regs[3];  // anchor #xxxx
				
				// append a new parameter if one or more already exist
				if ( $q )
					$q = $q . "&" . $append;
				else
					$q = $append;
					
				// rebuild full url
				return $u . '?' . $q . $a;
			}
			else
				// impossible case 
				return $url;
		}
		else
			return $url;
	}


	/**
     * Get a explanation for a file upload error ($FILE is either the PHP file record array or the error code)
     * 
     * @param array $file File record 
     * @return string Error message for upload
     */
	static function file_upload_error($file)
	{
		if ( is_array($file) )	
			$err = $file['error'];
		else
			$err = $file;
			
		switch ( $err )
		{
			case 1: // UPLOAD_ERR_INI_SIZE
			   return "The file is too big (see PHP.ini max upload size)";
			case 2: // UPLOAD_ERR_FORM_SIZE
			   return "The file is too big (see max upload size in the HTML form)";
			case 3: // UPLOAD_ERR_PARTIAL
				return "The upload has been interrupted before completion";
			case 4: // UPLOAD_ERR_NO_FILE
				return "The upload file is empty (0 byte)";
		}
		
		return "";
	}
	
	
    /**
     * Gget path from web root (not server root) for a given file (e.g. for home/user/www/mydir/myfile.html we return '/mydir')
     *
     * @param string $file File whose relative folder will be extracted
     * @return string Folder of $file, relative to web root
     */
	static function relativeFolder($file)
	{
		return substr(dirname($file), strlen(rtrim($_SERVER['DOCUMENT_ROOT'], '/')));
	}

}

?>