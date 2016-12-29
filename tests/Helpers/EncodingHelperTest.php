<?php


class EncodingHelperTest extends PHPUnit_Framework_TestCase
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
                            
                            \Nettools\Core\Helpers\EncodingHelper::fr_entities_encode('€;àäâ;ëêéè;ïî;öô;ùüû;ç;ÀÄÂ;ËÊÉÈ;ÏÎ;ÖÔ;ÙÜÛ;Ç')
                        );
    }
    
    
    public function testFr_entities_decode()
    {
        $this->assertEquals('€;àäâ;ëêéè;ïî;öô;ùüû;ç;ÀÄÂ;ËÊÉÈ;ÏÎ;ÖÔ;ÙÜÛ;Ç',
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
                            \Nettools\Core\Helpers\EncodingHelper::noAccents('accents:àäâ;ëêéè;ïî;öô;ùüû;ç;ÀÄÂ;ÉÈËÊ;ÏÎ;ÖÔ;ÙÜÛ;Ç')
                        );
    }
}

?>