<?php

// namespace
namespace Nettools\Core\Helpers;




// helper cass to deal with web-specific stuff (json, url, etc.)

class NetworkingHelper {
		
	// output http headers suitable for xmlhttp
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
	
		
	// append a parameter to an url
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


	// get a explanation for a file upload error ($FILE is either the PHP file record array or the error code)
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
	
	
    // get path from web root (not server root) for a given file (e.g. for home/user/www/mydir/myfile.html we return '/mydir')
	static function relativeFolder($file)
	{
		return substr(dirname($file), strlen(rtrim($_SERVER['DOCUMENT_ROOT'], '/')));
	}

}

?>