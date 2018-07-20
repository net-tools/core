<?php


namespace Nettools\Core\Misc\Tests;



class HtmlContentTest extends \PHPUnit\Framework\TestCase
{
	public function testHtmlContent()
	{
        $o = new \Nettools\Core\Misc\HtmlContent('abc');
		$this->assertEquals('abc', $o->html);
		$this->assertEquals('abc', $o);	// __toString
	}
	
	
	
}



?>
