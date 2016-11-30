<?php

// namespace
namespace Nettools\Core\Helpers;




// helper class to provide some basic security mechanisms
class SecurityHelper {
		
	// create a token, based on a unique value and a shared secret ; 64 characters + 13 characters
	static function createToken($secret = "stoken")
	{
		$unid = uniqid(); 	// 13 characters
		return hash('sha256', $unid . $secret) . $unid;
	}
	
	
	// check if token has been altered : last 13 characters are used to compute the 64 first characters.
	static function checkToken($token, $secret = "stoken")
	{
		$unid = substr($token, -13); // extract last 13 characters
		return (hash('sha256', $unid . $secret) . $unid) == $token;
	}
	
	
	// create a token with a expiration time ; by default, the token expires 60 seconds later
    // to create the token, you may provide the validity delay, the unit of the delay (seconds, minutes or hours) and a secret.
    // if no parameters, default values will be used
	static function createTimestampToken($graceperiod = 60, $period = "s", $root = 'token', $unid = NULL, $ts = NULL)
	{
		if ( $graceperiod > 255 )
            // we can only encode values less than 256, as we are dealing with bytes.
            // if we have an overflow and the delay unit is in seconds, we convert the delay to minutes
			if ( $period == 's' )
				return self::createTimestampToken(round($graceperiod / 60), 'm', $root, $ts);
				
            // if we have an overflow and the delay unit is in minutes, we convert the delay to hours
			else if ( $period == 'm' )
				return self::createTimestampToken(round($graceperiod / 60), 'h', $root, $ts);
				
            // if we have an overflow and the delay unit is in hours, we convert the delay to 255 hours
			else
				return self::createTimestampToken(255, 'h', $root, $ts);
			
		if ( is_null($ts) )
			$ts = time();
		if ( is_null($unid) )
			$unid = uniqid(); // 13 characters
			
		// if delay is < 10, we must use a leading 0
		$graceperiod = dechex($graceperiod);
		if ( strlen($graceperiod) == 1 )
			$graceperiod = "0$graceperiod";
			
		return hash('sha256', $root . $graceperiod . $period . $unid . $ts) . $graceperiod . $period . $unid . dechex($ts);
	}
	
	
	// check a timestamp token ; if altered OR expired, returning FALSE
	static function checkTimestampToken($token, $racine = 'token')
	{
		// checking format
		if ( !preg_match('/^[a-fA-F0-9]{64}[a-fA-F0-9]{2}(h|m|s)[a-fA-F0-9]{13}[a-fA-F0-9]+$/', $token) )
			return false;
	
		// extract the delay, delay unit and starting timestamp
		$graceperiod = hexdec(substr($token, 64, 2));
		$period = substr($token, 66, 1);
		$unid = substr($token, 67, 13);
		$ts = hexdec(substr($token, 80));
		
		
		// calc the delay in seconds
		if ( $period == 'h' )
			$graceperiod_seconds = $graceperiod*60*60;
		else if ( $period == 'm' )
			$graceperiod_seconds = $graceperiod*60;
		else
			$graceperiod_seconds = $graceperiod;
			
		
		// create the token with extracted data and check it has not been altered ; then check the time validity 
		return ($token == self::createTimestampToken($graceperiod, $period, $racine, $unid, $ts))
				&&
				($ts + $graceperiod_seconds > time());
	}
	
	
	// sanitize a string (detect html tags, add slashes and remove sql orders)
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
	
	
	// sanitize an array (detect html tags, add slashes and remove sql orders)
	static function sanitize_array(&$arr)
	{
		foreach ( $arr as $k => $v )
			$arr[$k] = self::sanitize($v);
			
		return $arr;
	}
	
	
	// clean a string by replacing all accented letters with their corresponding non-accented letters
    // and replacing spaces and single quote with -
	static function cleanString($s)
	{
		return str_replace(array(' ', "'"), '-', EncodingHelper::noAccents($s));
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