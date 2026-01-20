<?php

namespace Nettools\Core\Tests;




use \Nettools\Core\Helpers\SecureRequestHelper;
use \Nettools\Core\Helpers\SecureRequestHelper\AbstractBrowserInterface;


class BI extends AbstractBrowserInterface  
{
	public function setCookie($name, $value, $expires, $domain) {}

	public function deleteCookie($name, $domain) {}

	public function getCookie($name) {}
}




class SecureRequestHelperTest extends \PHPUnit\Framework\TestCase
{
    
    public function testCSRF()
    {
		$intf = $this->getMockBuilder(BI::class)->onlyMethods(['setCookie', 'deleteCookie', 'getCookie'])->getMock();
		$intf->expects($this->once())->method('setCookie');
		$cookie = 'abcdef';
		$intf->method('getCookie')->willReturn($cookie);
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);
		$sec->initializeCSRF();
		
		$this->assertEquals('_cname_', $sec->getCSRFCookieName());
		$this->assertEquals('_fcname_', $sec->getCSRFSubmittedValueName());
		$this->assertEquals($cookie, $sec->getCSRFCookie());
		$this->assertEquals("<input type=\"hidden\" name=\"_fcname_\" value=\"$cookie\">", $sec->addCSRFHiddenInput());
		
		
		$req = ['input1'=>'value1', '_fcname_'=>$cookie];
		$this->assertEquals(true, $sec->authorizeCSRF($req));
    }
	
	
	
	public function testAuthorizeCSRF()
	{
		$this->expectException(\Nettools\Core\Helpers\SecureRequestHelper\CSRFException::class);
		$this->expectExceptionMessage('CSRF security validation failed');
		
		
		$intf = $this->getMockBuilder(BI::class)->onlyMethods(['setCookie', 'deleteCookie', 'getCookie'])->getMock();
		$intf->expects($this->once())->method('setCookie');
		$cookie = 'abcdef';
		$intf->method('getCookie')->willReturn($cookie);
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);
		$sec->initializeCSRF();
		$req = ['input1'=>'value1', '_fcname_'=>'dummy value'];
		$sec->authorizeCSRF($req);
	}
	
	
	
	public function testAuthorizeCSRFNoCookie()
	{
	 	$this->expectException(\Nettools\Core\Helpers\SecureRequestHelper\CSRFException::class);
	 	$this->expectExceptionMessage('CSRF cookie has not been initialized');
		 
		 
		$intf = $this->getMockBuilder(BI::class)->onlyMethods(['setCookie', 'deleteCookie', 'getCookie'])->getMock();
		$intf->expects($this->never())->method('setCookie');
		$intf->method('getCookie')->willReturn('');
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);

		// no init
		$req = ['input1'=>'value1', '_fcname_'=>'dummy value'];
		$sec->authorizeCSRF($req);
	}
	
	
	
	public function testNotInitializeCSRF()
	{
		$this->expectException(\Nettools\Core\Helpers\SecureRequestHelper\CSRFException::class);
		$this->expectExceptionMessage('CSRF cookie has not been initialized');

			 
		$intf = $this->getMockBuilder(BI::class)->onlyMethods(['setCookie', 'deleteCookie', 'getCookie'])->getMock();
		$intf->expects($this->never())->method('setCookie');
		$intf->method('getCookie')->willReturn('');
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);
		
		// calling getCSRFCookie will throw an exception since cookie has not been initialized
		$sec->getCSRFCookie();
	}
	
	
	
	public function testRevokeCSRF()
	{
	 	$this->expectException(\Nettools\Core\Helpers\SecureRequestHelper\CSRFException::class);
	 	$this->expectExceptionMessage('CSRF cookie has not been initialized');

		
		
		$intf = $this->getMockBuilder(BI::class)->onlyMethods(['setCookie', 'deleteCookie', 'getCookie'])->getMock();
		$intf->expects($this->once())->method('setCookie');
		$intf->expects($this->once())->method('deleteCookie');
		$cookie = 'abcdef';
		$intf->method('getCookie')->willReturn($cookie, '');
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);
		$sec->initializeCSRF();
		$this->assertEquals($cookie, $sec->getCSRFCookie());	// returning cookie
		
		$sec->revokeCSRF();
		
		// calling getCSRFCookie will throw an exception since cookie has been revoked
		$sec->getCSRFCookie();									// returning ''
	}

    
}

?>