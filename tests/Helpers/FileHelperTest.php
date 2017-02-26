<?php

use \Nettools\Core\Helpers\FileHelper;



class FileHelperTest extends PHPUnit\Framework\TestCase
{
    
    public function testGuessMimeType()
    {
		$this->assertEquals('image/gif'           , FileHelper::guessMimeType('fich.gif', 'application/octet-stream'));
		$this->assertEquals('image/jpeg'          , FileHelper::guessMimeType('fich.jpg', 'application/octet-stream'));
		$this->assertEquals('image/jpeg'          , FileHelper::guessMimeType('fich.jpeg', 'application/octet-stream'));
		$this->assertEquals('image/png'           , FileHelper::guessMimeType('fich.png', 'application/octet-stream'));
		$this->assertEquals('application/pdf'     , FileHelper::guessMimeType('fich.pdf', 'application/octet-stream'));
		$this->assertEquals('application/pdf'     , FileHelper::guessMimeType('fich.gif.pdf', 'application/octet-stream'));
		$this->assertEquals('application/octet-stream', FileHelper::guessMimeType('fich.abc', 'application/octet-stream'));
		$this->assertEquals('text/html'           , FileHelper::guessMimeType('fich.htm', 'application/octet-stream'));
		$this->assertEquals('text/html'           , FileHelper::guessMimeType('fich.html', 'application/octet-stream'));
		$this->assertEquals('text/plain'          , FileHelper::guessMimeType('fich.txt', 'application/octet-stream'));
		$this->assertEquals('message/rfc822'      , FileHelper::guessMimeType('fich.eml', 'application/octet-stream'));
		$this->assertEquals('application/msword'  , FileHelper::guessMimeType('fich.doc', 'application/octet-stream'));
		$this->assertEquals('application/vnd.openxmlformats-officedocument.wordprocessingml.document', FileHelper::guessMimeType('fich.docx', 'application/octet-stream'));
		$this->assertEquals('application/vnd.ms-excel', FileHelper::guessMimeType('fich.xls', 'application/octet-stream'));
		$this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', FileHelper::guessMimeType('fich.xlsx', 'application/octet-stream'));
		$this->assertEquals('application/vnd.ms-powerpoint', FileHelper::guessMimeType('fich.ppt', 'application/octet-stream'));
		$this->assertEquals('application/vnd.ms-powerpoint', FileHelper::guessMimeType('fich.pps', 'application/octet-stream'));
		$this->assertEquals('application/vnd.openxmlformats-officedocument.presentationml.presentation', FileHelper::guessMimeType('fich.ppsx', 'application/octet-stream'));
		$this->assertEquals('application/vnd.openxmlformats-officedocument.presentationml.presentation', FileHelper::guessMimeType('fich.pptx', 'application/octet-stream'));
		$this->assertEquals('application/vnd.oasis.opendocument.text', FileHelper::guessMimeType('fich.odt', 'application/octet-stream'));
		$this->assertEquals('application/vnd.oasis.opendocument.spreadsheet', FileHelper::guessMimeType('fich.ods', 'application/octet-stream'));
		$this->assertEquals('application/vnd.oasis.opendocument.presentation', FileHelper::guessMimeType('fich.odp', 'application/octet-stream'));
		$this->assertEquals('application/zip'     , FileHelper::guessMimeType('fich.zip', 'application/octet-stream'));
    }


    public function testGuessFileTypeFromMimeType()
    {
		$this->assertEquals('pdf', FileHelper::guessFileTypeFromMimeType('application/pdf'));
		$this->assertEquals('html', FileHelper::guessFileTypeFromMimeType('text/html'));
		$this->assertEquals('text', FileHelper::guessFileTypeFromMimeType('text/plain'));
		$this->assertEquals('email', FileHelper::guessFileTypeFromMimeType('message/rfc822'));
		$this->assertEquals('word', FileHelper::guessFileTypeFromMimeType('application/msword'));
		$this->assertEquals('word', FileHelper::guessFileTypeFromMimeType('application/vnd.openxmlformats-officedocument.wordprocessingml.document'));
		$this->assertEquals('word', FileHelper::guessFileTypeFromMimeType('application/vnd.oasis.opendocument.text'));
		$this->assertEquals('excel', FileHelper::guessFileTypeFromMimeType('application/vnd.ms-excel'));
		$this->assertEquals('excel', FileHelper::guessFileTypeFromMimeType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'));
		$this->assertEquals('excel', FileHelper::guessFileTypeFromMimeType('application/vnd.oasis.opendocument.spreadsheet'));
		$this->assertEquals('powerpoint', FileHelper::guessFileTypeFromMimeType('application/vnd.ms-powerpoint'));
		$this->assertEquals('powerpoint', FileHelper::guessFileTypeFromMimeType('application/vnd.openxmlformats-officedocument.presentationml.presentation'));
		$this->assertEquals('powerpoint', FileHelper::guessFileTypeFromMimeType('application/vnd.oasis.opendocument.presentation'));
		$this->assertEquals('zip', FileHelper::guessFileTypeFromMimeType('application/zip'));
		$this->assertEquals('binary', FileHelper::guessFileTypeFromMimeType('application/unknown', 'binary'));
    }
    
}

?>