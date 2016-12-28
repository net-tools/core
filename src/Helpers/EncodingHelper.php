<?php
/**
 * EncodingHelper
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



// namespace
namespace Nettools\Core\Helpers;



/**
 * Helper class to deal with encodings
 */
class EncodingHelper {
	
	/**
     * Get a string where slashes are escaped, and newlines are escaped with a litteral \n. Carriage returns and new lines are transformed to a single \n
     *
     * @param string $s String to escape
     * @return string The string with special characters and newlines escaped
     */
	static function escape($s)
	{
		return str_replace(array("\r\n", "\n"), "\\n", addslashes($s));
	}
	
	
	/**
     * Encode French accented characters to html entities
     * 
     * @param string $s String to encode
     * @return string String encoded with html entities
     */
	static function fr_entities_encode($s)
	{
		//$s = str_replace("&", "&amp;", $s);
		$s = str_replace("é", "&eacute;", $s);
		$s = str_replace("è", "&egrave;", $s);
		$s = str_replace("ë", "&euml;", $s);
		$s = str_replace("ê", "&ecirc;", $s);
		$s = str_replace("à", "&agrave;", $s);
		$s = str_replace("ä", "&auml;", $s);
		$s = str_replace("â", "&acirc;", $s);
		$s = str_replace("ï", "&iuml;", $s);
		$s = str_replace("î", "&icirc;", $s);
		$s = str_replace("ö", "&ouml;", $s);
		$s = str_replace("ô", "&ocirc;", $s);
		$s = str_replace("ü", "&uuml;", $s);
		$s = str_replace("û", "&ucirc;", $s);
		$s = str_replace("ù", "&ugrave;", $s);
		$s = str_replace("ç", "&ccedil;", $s);
		$s = str_replace("€", "&euro;", $s);
	
		$s = str_replace("É", "&Eacute;", $s);
		$s = str_replace("È", "&Egrave;", $s);
		$s = str_replace("Ë", "&Euml;", $s);
		$s = str_replace("Ê", "&Ecirc;", $s);
		$s = str_replace("À", "&Agrave;", $s);
		$s = str_replace("Ä", "&Auml;", $s);
		$s = str_replace("Â", "&Acirc;", $s);
		$s = str_replace("Ï", "&Iuml;", $s);
		$s = str_replace("Î", "&Icirc;", $s);
		$s = str_replace("Ö", "&Ouml;", $s);
		$s = str_replace("Ô", "&Ocirc;", $s);
		$s = str_replace("Ü", "&Uuml;", $s);
		$s = str_replace("Û", "&Ucirc;", $s);
		$s = str_replace("Ù", "&Ugrave;", $s);
		$s = str_replace("Ç", "&Ccedil;", $s);
		
		return $s;
	}
	
	
    
    /** 
     * Decode html entities and euro symbol
     * 
     * @param string $s Decode a string encoded with HTML entities
     * @return string Decoded string
     */
	static function fr_entities_decode($s)
	{
		return str_replace("&euro;", "€", html_entity_decode($s));
	}
	
	
	
	/**
     * Replace accented characters by their non-accented equivalent
     * 
     * @param string $s String to process
     * @return string String with accents removed and replaced with non-accented characters
     */
	static function noAccents($s)
	{
		$s = str_replace(array('É', 'È', 'Ë', 'Ê'), 'E', $s);
		$s = str_replace(array('À', 'Ä', 'Â'), 'A', $s);
		$s = str_replace(array('Ï', 'Î'), 'I', $s);
		$s = str_replace(array('Ö', 'Ô'), 'O', $s);
		$s = str_replace(array('Ù', 'Ü' ,'Û'), 'U', $s);
		$s = str_replace('Ç', 'C', $s);
	
		$s = str_replace(array('é', 'è', 'ë', 'ê'), 'e', $s);
		$s = str_replace(array('à', 'ä', 'â'), 'a', $s);
		$s = str_replace(array('ï', 'î'), 'i', $s);
		$s = str_replace(array('ö', 'ô'), 'o', $s);
		$s = str_replace(array('ù', 'ü', 'û'), 'u', $s);
		$s = str_replace('ç', 'c', $s);
	
		return $s;
	}
    
    
}

?>