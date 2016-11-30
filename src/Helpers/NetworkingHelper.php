<?php

// namespace
namespace Nettools\Core\Helpers;




// helper cass to deal with web-specific stuff (json, url, etc.)
/*

json_xxx functions are meant to be chained :

echo json_response(true, json_message('Everything OK', json_value('mykey', 'myvalue')))

*/
class NetworkingHelper {
		
	// get a json formatted string with given content
	static function json_object($content)
	{
		return "{" . $content . "}";
	}
	
	
	// return a json message
	static function json_message($m, $next = NULL)
	{
		return self::json_value('message', $m, $next);
	}
	
	
	// get a raw json value
	static function json_rawValue($k, $v, $next = NULL)
	{
		// if key not null, creating "key":value pair
		if ( !is_null($k) )
			$ret = "\"$k\":$v";
		else
			$ret = $v;

        // chaining
		if ( $next )
			$ret = "$ret, $next";
			
		return $ret;
	}
	
	
	// encode a value to JSON
	static function json_value($k, $v, $next = NULL)
	{
		$v = self::jsonEncode($v);
		
		return self::json_rawValue($k, $v, $next);
	}
	
	
	// get a JSON response ("statut" field with true/false values to indicate whether the request has succeeded or not)
	static function json_response($statut, $next = NULL)
	{
		return self::json_object(self::json_value('statut', $statut, $next));
	}
	
		
	// encode JSON data, and deal with arrays : associative arrays are associative object litterals in json, but indexed PHP arrays
    // should be outputted as javascript arrays.
	static function jsonEncode($v)
	{
		if ( is_null($v) )
			return "null";
		else if ( is_bool($v) )
			return $v ? 'true' : 'false';
		else if ( is_int($v) || is_float($v) )
			return $v;
		else if ( is_string($v) )
			return json_encode($v);
		else if ( is_array($v) )
		{
			// if array not empty
			if ( count($v) )
			{
                $s = array();
				
                
                // if indexed array
				if ( !DataHelper::is_associative_array($v) )
				{
					foreach ( $v as $kv )
						$s[] = self::json_value(NULL, $kv);
					return "[" . implode(',', $s) . "]";
				}			
				else
				{
					// if associative array
					foreach ( $v as $kk=>$kv )
						$s[] = self::json_value($kk, $kv);
					return "{" . implode(',', $s) . "}";
				}		
			}
			else
				return "[]";
		}
		else
			return 'null';
	}
	
	
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