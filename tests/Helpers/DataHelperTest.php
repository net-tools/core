<?php


class DataHelperTest extends PHPUnit_Framework_TestCase
{
    private $_locale = NULL;

    
    public function setUp()
    {
        // backup current locale
        $this->_locale = setlocale(LC_TIME, 0);
    }
    
    
    public function tearDown()
    {
        // restore locale
        setlocale(LC_TIME, $this->_locale);
    }
    
    
    public function testNumber()
    {
        $this->assertSame('12,99', \Nettools\Core\Helpers\DataHelper::number(12.9889, 2));
        $this->assertSame('12,99', \Nettools\Core\Helpers\DataHelper::number("12.9889", 2));
        $this->assertSame('12,00', \Nettools\Core\Helpers\DataHelper::number(12, 2));
    }


    public function testLeadingZero()
    {
        $this->assertSame('00', \Nettools\Core\Helpers\DataHelper::leadingZero(0));
        $this->assertSame('01', \Nettools\Core\Helpers\DataHelper::leadingZero(1));
        $this->assertSame('10', \Nettools\Core\Helpers\DataHelper::leadingZero(10));
        $this->assertSame('00', \Nettools\Core\Helpers\DataHelper::leadingZero('0'));
        $this->assertSame('01', \Nettools\Core\Helpers\DataHelper::leadingZero('1'));
        $this->assertSame('10', \Nettools\Core\Helpers\DataHelper::leadingZero('10'));
    }


    public function testTrimLeadingZero()
    {
        $this->assertSame(10, \Nettools\Core\Helpers\DataHelper::trimLeadingZero(10));
        $this->assertSame(0, \Nettools\Core\Helpers\DataHelper::trimLeadingZero('0'));
        $this->assertSame(1, \Nettools\Core\Helpers\DataHelper::trimLeadingZero('01'));
        $this->assertSame(10, \Nettools\Core\Helpers\DataHelper::trimLeadingZero('10'));
    }


    public function testArray2Parameters()
    {
        $params = array(12, 'abc', '', 'def', NULL);
        $this->assertSame('12, "abc", "", "def", ""', \Nettools\Core\Helpers\DataHelper::array2Parameters($params, '"'));
    }


    public function testDate2Midnight()
    {
        $d0 = \Nettools\Core\Helpers\DataHelper::date2Midnight(time());
        $d = getDate($d0);
        $this->assertSame(0, $d['hours']);
        $this->assertSame(0, $d['minutes']);
        $this->assertSame(0, $d['seconds']);
        $this->assertEquals(date('d'), $d['mday']);
        $this->assertEquals(date('m'), $d['mon']);
        $this->assertEquals(date('Y'), $d['year']);
    }
    
    
    public function testMonth2str()
    {
        // set locale to en_US 
        setlocale(LC_TIME, 'en_US');
        $this->assertEquals('January', \Nettools\Core\Helpers\DataHelper::month2str(1));
        $this->assertEquals('February', \Nettools\Core\Helpers\DataHelper::month2str(2));
        $this->assertEquals('March', \Nettools\Core\Helpers\DataHelper::month2str(3));
        $this->assertEquals('April', \Nettools\Core\Helpers\DataHelper::month2str(4));
        $this->assertEquals('May', \Nettools\Core\Helpers\DataHelper::month2str(5));
        $this->assertEquals('June', \Nettools\Core\Helpers\DataHelper::month2str(6));
        $this->assertEquals('July', \Nettools\Core\Helpers\DataHelper::month2str(7));
        $this->assertEquals('August', \Nettools\Core\Helpers\DataHelper::month2str(8));
        $this->assertEquals('September', \Nettools\Core\Helpers\DataHelper::month2str(9));
        $this->assertEquals('October', \Nettools\Core\Helpers\DataHelper::month2str(10));
        $this->assertEquals('November', \Nettools\Core\Helpers\DataHelper::month2str(11));
        $this->assertEquals('December', \Nettools\Core\Helpers\DataHelper::month2str(12));

        setlocale(LC_TIME, 'fr_FR.utf8');
        $this->assertEquals('janvier', \Nettools\Core\Helpers\DataHelper::month2str(1));
        $this->assertEquals('février', \Nettools\Core\Helpers\DataHelper::month2str(2));
        $this->assertEquals('mars', \Nettools\Core\Helpers\DataHelper::month2str(3));
        $this->assertEquals('avril', \Nettools\Core\Helpers\DataHelper::month2str(4));
        $this->assertEquals('mai', \Nettools\Core\Helpers\DataHelper::month2str(5));
        $this->assertEquals('juin', \Nettools\Core\Helpers\DataHelper::month2str(6));
        $this->assertEquals('juillet', \Nettools\Core\Helpers\DataHelper::month2str(7));
        $this->assertEquals('août', \Nettools\Core\Helpers\DataHelper::month2str(8));
        $this->assertEquals('septembre', \Nettools\Core\Helpers\DataHelper::month2str(9));
        $this->assertEquals('octobre', \Nettools\Core\Helpers\DataHelper::month2str(10));
        $this->assertEquals('novembre', \Nettools\Core\Helpers\DataHelper::month2str(11));
        $this->assertEquals('décembre', \Nettools\Core\Helpers\DataHelper::month2str(12));
    }
    
    
    public function testMonth2shortstr()
    {
        // set locale to en_US 
        setlocale(LC_TIME, 'en_US');
        $this->assertEquals('Jan', \Nettools\Core\Helpers\DataHelper::month2shortstr(1));
        $this->assertEquals('Feb', \Nettools\Core\Helpers\DataHelper::month2shortstr(2));
        $this->assertEquals('Mar', \Nettools\Core\Helpers\DataHelper::month2shortstr(3));
        $this->assertEquals('Apr', \Nettools\Core\Helpers\DataHelper::month2shortstr(4));
        $this->assertEquals('May', \Nettools\Core\Helpers\DataHelper::month2shortstr(5));
        $this->assertEquals('Jun', \Nettools\Core\Helpers\DataHelper::month2shortstr(6));
        $this->assertEquals('Jul', \Nettools\Core\Helpers\DataHelper::month2shortstr(7));
        $this->assertEquals('Aug', \Nettools\Core\Helpers\DataHelper::month2shortstr(8));
        $this->assertEquals('Sep', \Nettools\Core\Helpers\DataHelper::month2shortstr(9));
        $this->assertEquals('Oct', \Nettools\Core\Helpers\DataHelper::month2shortstr(10));
        $this->assertEquals('Nov', \Nettools\Core\Helpers\DataHelper::month2shortstr(11));
        $this->assertEquals('Dec', \Nettools\Core\Helpers\DataHelper::month2shortstr(12));


        setlocale(LC_TIME, 'fr_FR.utf8');
        $this->assertEquals('janv.', \Nettools\Core\Helpers\DataHelper::month2shortstr(1));
        $this->assertEquals('févr.', \Nettools\Core\Helpers\DataHelper::month2shortstr(2));
        $this->assertEquals('mars', \Nettools\Core\Helpers\DataHelper::month2shortstr(3));
        $this->assertEquals('avril', \Nettools\Core\Helpers\DataHelper::month2shortstr(4));
        $this->assertEquals('mai', \Nettools\Core\Helpers\DataHelper::month2shortstr(5));
        $this->assertEquals('juin', \Nettools\Core\Helpers\DataHelper::month2shortstr(6));
        $this->assertEquals('juil.', \Nettools\Core\Helpers\DataHelper::month2shortstr(7));
        $this->assertEquals('août', \Nettools\Core\Helpers\DataHelper::month2shortstr(8));
        $this->assertEquals('sept.', \Nettools\Core\Helpers\DataHelper::month2shortstr(9));
        $this->assertEquals('oct.', \Nettools\Core\Helpers\DataHelper::month2shortstr(10));
        $this->assertEquals('nov.', \Nettools\Core\Helpers\DataHelper::month2shortstr(11));
        $this->assertEquals('déc.', \Nettools\Core\Helpers\DataHelper::month2shortstr(12));
    }
    
    
    public function testIs_associative_array()
    {
        $this->assertTrue(\Nettools\Core\Helpers\DataHelper::is_associative_array(array('key'=>'value')));
        $this->assertFalse(\Nettools\Core\Helpers\DataHelper::is_associative_array(array('value')));
        $this->assertFalse(\Nettools\Core\Helpers\DataHelper::is_associative_array(array()));
        $this->assertTrue(\Nettools\Core\Helpers\DataHelper::is_associative_array(array(), true));
        $this->assertFalse(\Nettools\Core\Helpers\DataHelper::is_associative_array('aaa'));
		$this->assertTrue(\Nettools\Core\Helpers\DataHelper::is_associative_array(array('0'=>'g', '1.5'=>'h')));  // float key !
		$this->assertTrue(\Nettools\Core\Helpers\DataHelper::is_associative_array(array(0=>'i', 2=>'j'))); // keys with a gap between 0 and 1 key
    }
    
    
    public function testCapsFirstLetters()
    {
        $this->assertEquals('Ab Cd', \Nettools\Core\Helpers\DataHelper::capsFirstLetters('ab cd'));
        $this->assertEquals('Ab-Cd', \Nettools\Core\Helpers\DataHelper::capsFirstLetters('ab-cd'));
    }
    
    
    public function testAbbreviate()
    {
        $this->assertEquals('A. C.', \Nettools\Core\Helpers\DataHelper::abbreviate('ab cd'));
        $this->assertEquals('A.-C.', \Nettools\Core\Helpers\DataHelper::abbreviate('ab-cd'));
    }

    
    public function testString2associativeArray()
    {
		$this->assertEquals(array('ab'=>'cd', 'ef'=>'gh'), \Nettools\Core\Helpers\DataHelper::string2associativeArray('ab=cd&ef=gh', '&', '='));
		$this->assertEquals(array('ab'=>'cd', 'ef'=>'gh'), \Nettools\Core\Helpers\DataHelper::string2associativeArray('ab=cd&ef=gh&', '&', '='));
		$this->assertEquals(array('ab'=>'cd'), \Nettools\Core\Helpers\DataHelper::string2associativeArray('ab=cd', '&', '='));
		$this->assertEquals(array('ab'=>''), \Nettools\Core\Helpers\DataHelper::string2associativeArray('ab=', '&', '='));
		$this->assertEquals(array('ab'=>'default'), \Nettools\Core\Helpers\DataHelper::string2associativeArray('ab=', '&', '=', 'default'));
		$this->assertEquals(array(), \Nettools\Core\Helpers\DataHelper::string2associativeArray('abcd', '&', '='));
		$this->assertEquals(array(), \Nettools\Core\Helpers\DataHelper::string2associativeArray('', '&', '='));
    }
    
    
    public function testMb_str_pad()
    {
		$this->assertEquals('abé...', \Nettools\Core\Helpers\DataHelper::mb_str_pad('abé', 6, '.'));
        
    }
}

?>