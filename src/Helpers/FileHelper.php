<?php

// namespace
namespace Nettools\Core\Helpers;



/** 
 * Helper class to deal with files
 */
class FileHelper {
	
	/**
     * Guess a file type (image, video, text, ...) from it's name
     * 
     * @param string $file Filename to process
     * @param string $default Default file type in case the file type can't be identified
     * @return string File type identified
     */
	static function guessFileType($file, $default = '')
	{
		return self::guessFileTypeFromMimeType(self::guessMimeType($file), $default);
	}
	
	
	/**
     * Guess file type (image, video, text, ...) from Mime type
     *
     * @param string $mt Mime type to process
     * @param string $def Default file type to return if Mime type is unknown
     * @return string File type identified
     */
	static function guessFileTypeFromMimeType($mt, $def = '')
	{
		// for audio, image, videos, this is simple, the Mime type is explicit
		if ( strpos($mt, 'audio/') === 0 )
			return 'audio';
		if ( strpos($mt, 'image/') === 0 )
			return 'image';
		if ( strpos($mt, 'video/') === 0 )
			return 'video';
		
			
		switch ( $mt )
		{
			case 'application/pdf':
				return 'pdf';
			case 'text/html':
				return 'html';
			case 'text/plain':
				return 'text';
			case 'message/rfc822':
				return 'email';
			case 'application/msword':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'application/vnd.oasis.opendocument.text':
				return 'word';
			case 'application/vnd.ms-excel':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'application/vnd.oasis.opendocument.spreadsheet':
				return 'excel';
			case 'application/vnd.ms-powerpoint':
			case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			case 'application/vnd.oasis.opendocument.presentation':
				return 'powerpoint';
			case 'application/zip':
				return 'zip';
			default :
				return $def;
		}
	}
	
	
	/**
     * Guess Mime type from file name
     *
     * @param string $file Filename to process
     * @param string $def Default value for Mime type
     * @return string The Mime type guessed from the filename
     */
	static function guessMimeType($file, $def = 'application/octet-stream')
	{
		// extract file extension (after . symbol)
		$ext = substr(strrchr(strtolower($file), '.'), 1);
	
		switch ( $ext )
		{
			case 'gif':
			case 'jpeg':
			case 'png':
				return "image/$ext";
			case 'jpg':
				return 'image/jpeg';
			case 'mp4':
			case 'mpeg':
			case 'avi':
				return "video/$ext";
			case 'mp3':
				return 'audio/mpeg3';
			case 'wav':
				return 'audio/wav';
			case 'pdf':
				return 'application/pdf';
			case 'htm':
			case 'html':
				return 'text/html';
			case 'txt':
				return 'text/plain';
			case 'eml':
				return 'message/rfc822';
			case 'doc':
				return 'application/msword';
			case 'docx':
				return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
			case 'xls':
				return 'application/vnd.ms-excel';
			case 'xlsx':
				return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
			case 'ppt':
			case 'pps':
				return 'application/vnd.ms-powerpoint';
			case 'pptx':
			case 'ppsx':
				return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
			case 'odt':
				return 'application/vnd.oasis.opendocument.text';
			case 'ods':
				return 'application/vnd.oasis.opendocument.spreadsheet';
			case 'odp':
				return 'application/vnd.oasis.opendocument.presentation';
			case 'zip':
				return 'application/zip';
			default :
				return $def;
		}
	}
}

?>