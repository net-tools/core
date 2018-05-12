<?php
/**
 * SecurityHelper
 *
 * @author Pierre - dev@net-tools.ovh
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
		
		// apply stripslashes if magic_quotes_gpc is enabled
		if(get_magic_quotes_gpc())
			$data = stripslashes($data);
			
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
     * Sanitize an array (detect html tags, add slashes and remove sql orders)
     * 
     * @param string[] &$arr Sanitize an array of strings ; original array is modified and returned for convenience
     * @return string[] The sanitized array is returned
     */
	static function sanitize_array(&$arr)
	{
		foreach ( $arr as $k => $v )
			$arr[$k] = self::sanitize($v);
			
		return $arr;
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
	
	
	
	/**
	* Decrypt data from a CryptoJS json encoding string
	*
	* @param mixed $passphrase
	* @param mixed $jsonString
	* @return mixed
	*/
	static function cryptoJsAesDecrypt($passphrase, $jsonString){
		$jsondata = json_decode($jsonString, true);
		try {
			$salt = hex2bin($jsondata["s"]);
			$iv  = hex2bin($jsondata["iv"]);
		} catch(Exception $e) { return null; }
		$ct = base64_decode($jsondata["ct"]);
		$concatedPassphrase = $passphrase.$salt;
		$md5 = array();
		$md5[0] = md5($concatedPassphrase, true);
		$result = $md5[0];
		for ($i = 1; $i < 3; $i++) {
			$md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
			$result .= $md5[$i];
		}
		$key = substr($result, 0, 32);
		$data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
		return json_decode($data, true);
	}
	
	
	/**
	* Encrypt value to a cryptojs compatiable json encoding string
	*
	* @param mixed $passphrase
	* @param mixed $value
	* @return string
	*/
	static function cryptoJsAesEncrypt($passphrase, $value){
		$salt = openssl_random_pseudo_bytes(8);
		$salted = '';
		$dx = '';
		while (strlen($salted) < 48) {
			$dx = md5($dx.$passphrase.$salt, true);
			$salted .= $dx;
		}
		$key = substr($salted, 0, 32);
		$iv  = substr($salted, 32,16);
		$encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
		$data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
		return json_encode($data);
	}
}

?>