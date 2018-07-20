<?php


namespace Nettools\Core\Misc\Tests;




class PdfCreatorTest extends \PHPUnit\Framework\TestCase
{
	protected $_fname;
	
	
	public function setUp()
	{
		$this->_fname = tempnam(sys_get_temp_dir(), 'pdfcreatortest');
	}
	
	
	
	public function tearDown()
	{
		if ( file_exists($this->_fname) )
			unlink($this->_fname);
	}
	
	
	
    public function testPdfCreator()
    {
        $h = new \Nettools\Core\Misc\HtmlContent('Test creation PDF');
		
		$creator = new \Nettools\Core\Misc\PdfCreator(__DIR__ . '/tcpdf_config.php');
		$path = $creator->create($h, $this->_fname, 'Invoice', 'My author');
		$fsize = filesize($this->_fname);
		$this->assertEquals('string', gettype($path));
		$this->assertEquals(true, ($fsize > 6500) && ($fsize < 7000));
    }
    
}



?>
