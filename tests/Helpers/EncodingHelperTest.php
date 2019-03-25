<?php

namespace Nettools\Core\Tests;
use \PHPUnit\Framework\TestCase;




class EncodingHelperTest extends TestCase
{
    
    public function testEscape()
    {
		$this->assertEquals('ab\\\'cd\\\\ef\\ngh\\nij', \Nettools\Core\Helpers\EncodingHelper::escape("ab'cd\\ef\r\ngh\nij"));    
    }
    
    
    public function testFr_entities_encode()
    {
		$this->assertEquals('&euro;;' .
							'&agrave;&auml;&acirc;;' .
							'&euml;&ecirc;&eacute;&egrave;;'.
							'&iuml;&icirc;;' .
							'&ouml;&ocirc;;' .
							'&ugrave;&uuml;&ucirc;;' .
							'&ccedil;;' .
							'&Agrave;&Auml;&Acirc;;' .
							'&Euml;&Ecirc;&Eacute;&Egrave;;'.
							'&Iuml;&Icirc;;' .
							'&Ouml;&Ocirc;;' .
							'&Ugrave;&Uuml;&Ucirc;;' .
							'&Ccedil;',
                            
                            \Nettools\Core\Helpers\EncodingHelper::fr_entities_encode('â‚¬;Ã Ã¤Ã¢;Ã«ÃªÃ©Ã¨;Ã¯Ã®;Ã¶Ã´;Ã¹Ã¼Ã»;Ã§;Ã€Ã„Ã‚;Ã‹ÃŠÃ‰Ãˆ;Ã�ÃŽ;Ã–Ã”;Ã™ÃœÃ›;Ã‡')
                        );
    }
    
    
    public function testFr_entities_decode()
    {
        $this->assertEquals('â‚¬;Ã Ã¤Ã¢;Ã«ÃªÃ©Ã¨;Ã¯Ã®;Ã¶Ã´;Ã¹Ã¼Ã»;Ã§;Ã€Ã„Ã‚;Ã‹ÃŠÃ‰Ãˆ;Ã�ÃŽ;Ã–Ã”;Ã™ÃœÃ›;Ã‡',
                            \Nettools\Core\Helpers\EncodingHelper::fr_entities_decode('&euro;;' .
                                            '&agrave;&auml;&acirc;;' .
                                            '&euml;&ecirc;&eacute;&egrave;;'.
                                            '&iuml;&icirc;;' .
                                            '&ouml;&ocirc;;' .
                                            '&ugrave;&uuml;&ucirc;;' .
                                            '&ccedil;;' .
                                            '&Agrave;&Auml;&Acirc;;' .
                                            '&Euml;&Ecirc;&Eacute;&Egrave;;'.
                                            '&Iuml;&Icirc;;' .
                                            '&Ouml;&Ocirc;;' .
                                            '&Ugrave;&Uuml;&Ucirc;;' .
                                            '&Ccedil;')
                        );
    }
    
    
    public function testnoAccents()
    {    
    	$this->assertEquals('accents:aaa;eeee;ii;oo;uuu;c;AAA;EEEE;II;OO;UUU;C', 
                            \Nettools\Core\Helpers\EncodingHelper::noAccents('accents:Ã Ã¤Ã¢;Ã«ÃªÃ©Ã¨;Ã¯Ã®;Ã¶Ã´;Ã¹Ã¼Ã»;Ã§;Ã€Ã„Ã‚;Ã‰ÃˆÃ‹ÃŠ;Ã�ÃŽ;Ã–Ã”;Ã™ÃœÃ›;Ã‡')
                        );
    }
}

?>