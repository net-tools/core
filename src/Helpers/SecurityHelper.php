<?php
/**
 * SecurityHelper
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */



// namespace
namespace Nettools\Core\Helpers;




/**
 * Helper class to provide some basic security mechanisms
 */
class SecurityHelper {
	
	/**
     * sanitize a string (detect html tags, add slashes and remove sql orders)
     * 
     * @param string $data String to sanitize
     * @return string Sanitized string
     */
	static function sanitize($data)
	{
		// remove whitespaces (not a must though)
		//$data = filter_var(trim($data), FILTER_SANITIZE_STRING, !FILTER_FLAG_STRIP_LOW);
		$data = trim(strip_tags($data));
		
		// apply stripslashes if magic_quotes_gpc is enabled // DEPRECATED PHP7.4
		/*if(get_magic_quotes_gpc())
			$data = stripslashes($data);*/ 
			
		// SQL commands are forbidden
		$banlist = array
			(
			"/alter /i", "/insert /i", "/select /i", "/update /i", "/delete /i", "/distinct /i", "/having /i", "/truncate /i", "/replace /i",
			"/handler /i", "/like /i", "/procedure /i", "/limit /i", "/order by /i", "/group by /i" , "/drop /i", "/database /i"
			);
			
		$data = preg_replace($banlist, '', $data);
		
		return $data;
	}
	
	
	/** 
     * Sanitize an array (detect html tags, add slashes and remove sql orders) ; in-place method
     * 
     * @param string[] &$arr Sanitize an array of strings ; original array is modified and returned for convenience
     * @return string[] The sanitized array is returned, but also in-place updated
     */
	static function sanitize_array(&$arr)
	{
		foreach ( $arr as $k => $v )
			$arr[$k] = self::sanitize($v);
			
		return $arr;
	}
	
	
	/** 
     * Sanitize an array (detect html tags, add slashes and remove sql orders) and returns a copy of the array
     * 
     * @param string[] $arr Sanitize an array of strings ; array not modified
     * @return string[] A sanitized copy of the original array is returned
     */
	static function sanitize_array_return($arr)
	{
		$ret = [];
		foreach ( $arr as $k => $v )
			$ret[$k] = self::sanitize($v);
			
		return $ret;
	}
	
	
	/**
     * Clean a string by replacing all accented letters with their corresponding non-accented letters
     * and replacing all non letters and digits by a default character.
     *
     * Useful for removing quotes and spaces which are not allowed on some filesystems.
     * 
     * @param string $s String to clean
     * @param string $replacement Character to use instead of the removed characters
     * @return string Cleaned string (only digits, letters, underscore, dot, hyphen)
     */
	static function cleanString($s, $replacement = '-')
	{
		return preg_replace('/[^a-zA-Z0-9_.-]/', '-', EncodingHelper::noAccents($s));
	}
}

?>