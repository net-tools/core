<?php

// namespace
namespace Nettools\Core\Helpers;




// helper class to handle strings, numbers and various formats
class DataHelper {
	
	// get a number with ',' as decimal separator, with DEC digits after decimal
	static function number($n, $dec)
	{
		return str_replace(".", ",", sprintf("%01.$dec" ."f", $n));
	}
	
	
	// format a number (stictly lower than 10) with a leading zero ; useful to format hours and dates (e.g. 9 becomes 09)
	static function leadingZero($n)
	{
		if ( $n < 10 )
			return "0$n";
		else
			return "$n";
	}
	
	
	// remove a leading zero from a number (useful to remove the left 0 from hours and dates < 10)
	static function trimLeadingZero($n)
	{
		if ( substr($n,0,1) == "0" )
			return (integer)(substr($n, 1));
		else
			return (integer)$n;
	}
	
	
	// convert an array to a commas separated string of parameters ; strings in array are enclosed with a given delimiter, number are outputed directly
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
	
	
    // reset a given unix timestamp to midnight
	static function date2midnight($d)
	{
		return mktime(0, 0, 0, date("n", $d), date("j", $d), date("Y", $d));
	}
    
	
	// convert a month (1..12) to string
	static function month2str($m)
	{
        $d = mktime(0, 0, 0, $m, 1, 2000);
        return strftime('%B', $d);
	}
			
	
	// convert a month (1..12) to string (short name for month)
	static function month2shortstr($m)
	{
        $d = mktime(0, 0, 0, $m, 1, 2000);
        return strftime('%b', $d);
	}
			
			
	// test if a given array is associative or has only numeric keys
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
	
		
	// capitalize first letters in a string (useful to have caps on a "surname name" string)
	static function capsFirstLetters($s)
	{
		return mb_convert_case($s, MB_CASE_TITLE);
	}
	
	
	// abbreviate a string by returning only the first letter of each word. Useful for abbreviating a list of surnames or compound names (e.g. John-Henry becomes J.-H.)
	static function abbreviate($s)
	{
		// ecrire les initiales du prénom en majuscules
		return mb_ereg_replace_callback('([^. -])[^. -]*(\\. -)?', create_function('$match','return mb_strtoupper($match[1]).".";'), $s);
	}
	
		
	// convenient function to extract data from a string through a regular expression, and get data in an associative array
    // (instead of numeric indexes for capturing parenthesis).
    // the function set all matching substrings (preg_match_all) in the matches paremeters (passed by reference), so the
    // returned array is in fact an array of array : first index is the n-substring matching, second index is the named parenthesis data
    // - $pattern : PCRE regular expression
	// - $vars : array of strings, naming capturing parenthesis in their order of appearance in the pattern.
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
	
	
	// decode a string to an associative array ; useful to decode url parameters to array : e.g. file=my.txt&user=me becomes the array ['file'=>'my.txt','user'=>'me']
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
	

    // synonymous for string2associativeArray, with default values for separators
	static function explodeAssociativeArray($str, $sep = ';', $sepval = '=', $valIfEmpty = NULL)
	{
		return self::string2associativeArray($str, $sep, $sepval, $valIfEmpty);
	}
	
	
	// utf8 compliant str_pad
	static function mb_str_pad($input, $pad_length, $pad_string=' ', $pad_type=STR_PAD_RIGHT)
	{
		// if utf8 characters, strlen > mb_strlen (as unicode characters may take 2 or more bytes ; strlen does not support multibyte characters)
		$diff = strlen($input) - mb_strlen($input);
		return str_pad($input, $pad_length+$diff, $pad_string, $pad_type);
	}

}

?>