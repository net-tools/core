<?php

namespace Nettools\Core\Tests;




use \Nettools\Core\Helpers\SecureRequestHelper;
use \Nettools\Core\Helpers\SecureRequestHelper\AbstractBrowserInterface;



class SecureRequestHelperTest extends \PHPUnit\Framework\TestCase
{
    
    public function testCSRF()
    {
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->once())->method('setCookie');
		$cookie = 'abcdef';
		$intf->method('getCookie')->will($this->returnValue($cookie));
		
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
	
	
	
    public function testHashedCSRF()
    {
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->once())->method('setCookie');
		$cookie = 'abcdef';
		$intf->method('getCookie')->will($this->returnValue($cookie));
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_', 'secret');
		$sec->setBrowserInterface($intf);
		$sec->initializeCSRF();
		
		$this->assertNotEquals($cookie, $sec->getHashedCSRFCookie());
		$this->assertEquals(true, strpos($sec->getHashedCSRFCookie(), '!') === 0);
		
		
		$req = ['input1'=>'value1', '_fcname_'=>$sec->getHashedCSRFCookie()];
		$this->assertEquals(true, $sec->authorizeCSRF($req));
    }
	
	
	
	public function testAuthorizeCSRF()
	{
		$this->expectException(\Nettools\Core\Helpers\SecureRequestHelper\CSRFException::class);
		$this->expectExceptionMessage('CSRF security validation failed');
		
		
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->once())->method('setCookie');
		$cookie = 'abcdef';
		$intf->method('getCookie')->will($this->returnValue($cookie));
		
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
		 
		 
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->never())->method('setCookie');
		$intf->method('getCookie')->will($this->returnValue(''));
		
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

			 
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->never())->method('setCookie');
		$intf->method('getCookie')->will($this->returnValue(''));
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);
		
		// calling getCSRFCookie will throw an exception since cookie has not been initialized
		$sec->getCSRFCookie();
	}
	
	
	
	public function testRevokeCSRF()
	{
	 	$this->expectException(\Nettools\Core\Helpers\SecureRequestHelper\CSRFException::class);
	 	$this->expectExceptionMessage('CSRF cookie has not been initialized');

		
		
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->once())->method('setCookie');
		$intf->expects($this->once())->method('deleteCookie');
		$cookie = 'abcdef';
		$intf->method('getCookie')->will($this->onConsecutiveCalls($cookie, ''));
		
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