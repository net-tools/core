<?php

// namespace
namespace Nettools\Core\Helpers;




/**
 * Helper class to handle strings, numbers and various formats
 */
class DataHelper {
	
	/**
     * Get a number with ',' as decimal separator, with DEC digits after decimal
     *
     * @param string $n Number to format
     * @param int $dec Number of digits after decimal separator
     * @return string The number formatted
     */
	static function number($n, $dec)
	{
		return str_replace(".", ",", sprintf("%01.$dec" ."f", $n));
	}
	
	
	/** 
     * Format a number (strictly lower than 10) with a leading zero
     *
     * Useful to format hours and dates (e.g. 9 becomes 09)
     * 
     * @param int $n Number to format
     * @return string The number with a leading 0 if $n is lower than 10
     */
	static function leadingZero($n)
	{
		if ( $n < 10 )
			return "0$n";
		else
			return "$n";
	}
	
	
	/**
     * Remove a leading zero from a number (useful to remove the left 0 from hours and dates < 10)
     * 
     * @param string $n Number to format
     * @return int The number with no leading zero
     */
	static function trimLeadingZero($n)
	{
		if ( substr($n,0,1) == "0" )
			return (integer)(substr($n, 1));
		else
			return (integer)$n;
	}
	
	
	/**
     * Convert an array to a commas separated string of parameters 
     *
     * Srings in array are enclosed with a given delimiter, number are outputed directly
     *
     * @param string[] $params An associative array to convert to a string
     * @param string $stringdelim Delimiters for strings (usually ' or ")
     * @return string Return a string with the array imploded (values separated by ,)
     */
	static function array2Parameters($params, $stringdelim)
	{
		$s = "";
		
		foreach ( $params as $p )
		{
			if ( is_int($p) )
				$s .= "$p, ";
			else
				$s .= $stringdelim . $p . $stringdelim . ", ";
		}
				
		if ( $s )
			// remove last ', ' (comma and space)
			$s = substr($s, 0, -2);
			
		return $s;
	}
	
	
    /**
    * Reset a given unix timestamp to midnight
    *
    * @param int $d Timestamp to reset to midnight
    * @return int Timestamp resetted
    */
	static function date2midnight($d)
	{
		return mktime(0, 0, 0, date("n", $d), date("j", $d), date("Y", $d));
	}
    
	
	/**
     * Convert a month (1 to 12) to string
     * 
     * @param int $m Month number (1 to 12)
     * @return string Month number converted to month name, according to locale settings
     */
	static function month2str($m)
	{
        $d = mktime(0, 0, 0, $m, 1, 2000);
        return strftime('%B', $d);
	}
			
	
	/** Convert a month (1 to 12) to string (short name for month)
     *
     * @param int $m Month number (1 to 12)
     * @return string Month number converted to a short month name, according to locale settings
     */
	static function month2shortstr($m)
	{
        $d = mktime(0, 0, 0, $m, 1, 2000);
        return strftime('%b', $d);
	}
			
			
	/**
     * Test if a given array is associative or has only numeric keys
     *
     * @param string[] $a Array to test
     * @return bool True if array $a is associative, false otherwise
     */
	static function is_associative_array($a)
	{
		// firstly, verify that all keys are ints ; if not, the array is associative
		$keys = array_keys($a);
		foreach ( $keys as $k )
			if ( !is_int($k) )
				return true;
				
        // if all keys are numeric, they must follow themselves : e.g. 0, 1, 2, 3 is OK, but 0, 3, 4 is NOT OK.
		return max($keys)+1 != count($a);
	}
	
		
	/**
     * Capitalize first letters of each word in a string (useful to have caps on a "surname name" string)
     *
     * @param string $s String to process
     * @return string String with first letter of each word uppercased
     */
	static function capsFirstLetters($s)
	{
		return mb_convert_case($s, MB_CASE_TITLE);
	}
	
	
	/** 
     * Abbreviate a string by returning only the first letter of each word. 
     * 
     * Useful for abbreviating a list of surnames or compound names (e.g. John-Henry becomes J.-H.)
     *
     * @param string $s String to process
     * @return string String with words abbreviated
     */
	static function abbreviate($s)
	{
		// ecrire les initiales du prénom en majuscules
		return mb_ereg_replace_callback('([^. -])[^. -]*(\\. -)?', create_function('$match','return mb_strtoupper($match[1]).".";'), $s);
	}
	
		
	/**
     * Convenient function to extract data from a string through a regular expression.
     *
     * We get data in an associative array (instead of numeric indexes for capturing parenthesis). 
     * The function set all matching substrings (preg_match_all) in the matches paremeters (passed by reference), so the
     * returned array is in fact an array of array : first index is the n-substring matching, second index is the named parenthesis data.
     
     * @param string $pattern PCRE regular expression
     * @param string $buffer String to be searched 
     * @param string[] $vars Array of strings, naming capturing parenthesis in their order of appearance in the pattern
     * @param string[] $matches Array of matches (passed by reference)
     * @return bool Return true of false (no match)
     */
    static function matchAll($pattern, $buffer, $vars, &$matches)
	{
		$tmp = array();
		if ( preg_match_all($pattern, $buffer, $tmp, PREG_SET_ORDER) === FALSE )
			return false;
		else
		{
			// for all matching substrings
			for ( $m = 0 ; $m < count($tmp) ; $m++ )
			{
				$res = array();
				
				// for all named capturing parenthesis 
				for ( $v = 0 ; $v < count($vars) ; $v++ )
					// if this capture group must be returned (name not empty)
					if ( $vars[$v] != '' )
						$res[$vars[$v]] = $tmp[$m][1+$v];		//$matches[0] = full matching string, first matching parenthesis is at column 1
					
				// new matching substring to return
				$matches[] = $res;
			}
			
		
			return true;
		}
	}
	
	
	/**
     * Decode a string to an associative array
     * 
     * Useful to decode url parameters to array : e.g. file=my.txt&user=me becomes the array ['file'=>'my.txt','user'=>'me']
     * 
     * @param string $str String to process
     * @param string $sep Separator of values (e.g. '&' in a querystring)
     * @param string $sepval Separator between key and value (e.g. '=' in a querystring)
     * @param string $valIfEmpty Default value if value is empty
     * @return string[] String converted to an associative array
     */
	static function string2associativeArray($str, $sep, $sepval, $valIfEmpty = NULL)
	{
		$str = explode($sep, $str);
		$ret = array();
		
		// for each items
		foreach ( $str as $s )
		{
			// get key and value
			$s = explode($sepval, $s);
			if ( count($s) == 2 )	// we should always have 2 items here : the key and the value
				$ret[$s[0]] = ($s[1]!='') ? $s[1] : $valIfEmpty;
		}
		
		return $ret;
	}
	

    /** 
     * Synonymous for string2associativeArray, with default values for separators
     * @param string $str String to process
     * @param string $sep Separator of values (e.g. '&' in a querystring)
     * @param string $sepval Separator between key and value (e.g. '=' in a querystring)
     * @param string $valIfEmpty Default value if value is empty
     * @return string[] String converted to an associative array
    */
	static function explodeAssociativeArray($str, $sep = ';', $sepval = '=', $valIfEmpty = NULL)
	{
		return self::string2associativeArray($str, $sep, $sepval, $valIfEmpty);
	}
	
	
	/** 
     * utf8 compliant str_pad
     * 
     * @param string $input String to pad
     * @param int $pad_length Length of final string
     * @param string $pad_string Character to use for padding
     * @param int $pad_type Pad left or right
     */
	static function mb_str_pad($input, $pad_length, $pad_string=' ', $pad_type=STR_PAD_RIGHT)
	{
		// if utf8 characters, strlen > mb_strlen (as unicode characters may take 2 or more bytes ; strlen does not support multibyte characters)
		$diff = strlen($input) - mb_strlen($input);
		return str_pad($input, $pad_length+$diff, $pad_string, $pad_type);
	}

}

?>