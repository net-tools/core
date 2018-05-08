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
		$this->assertEquals(true, $this->authorizeCSRF($req));
    }
	
	
	
	/**
	 * @expectedException \Nettools\Core\Helpers\SecureRequestHelper\CSRFException
	 * @expectedExceptionMessage CSRF security validation failed
	 */
	public function testAuthorizeCSRF()
	{
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->once())->method('setCookie');
		$cookie = 'abcdef';
		$intf->method('getCookie')->will($this->returnValue($cookie));
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);
		$sec->initializeCSRF();
		$req = ['input1'=>'value1', '_fcname_'=>'dummy value'];
		$this->authorizeCSRF($req);
	}
	
	
	
	/**
	 * @expectedException \Nettools\Core\Helpers\SecureRequestHelper\CSRFException
	 * @expectedExceptionMessage CSRF cookie has not been initialized
	 */
	public function testAuthorizeCSRFNoCookie()
	{
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->never())->method('setCookie');
		$intf->method('getCookie')->will($this->returnValue(''));
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);

		// no init
		$req = ['input1'=>'value1', '_fcname_'=>'dummy value'];
		$this->authorizeCSRF($req);
	}
	
	
	
	/**
	 * @expectedException \Nettools\Core\Helpers\SecureRequestHelper\CSRFException
	 * @expectedExceptionMessage CSRF cookie has not been initialized
	 */
	public function testNotInitializeCSRF()
	{
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->once())->method('setCookie');
		$intf->method('getCookie')->will($this->returnValue(''));
		
		$sec = new SecureRequestHelper('_cname_', '_fcname_');
		$sec->setBrowserInterface($intf);
		
		// calling getCSRFCookie will throw an exception since cookie has not been initialized
		$sec->getCSRFCookie();
	}
	
	
	
	/**
	 * @expectedException \Nettools\Core\Helpers\SecureRequestHelper\CSRFException
	 * @expectedExceptionMessage CSRF cookie has not been initialized
	 */
	public function testRevokeCSRF()
	{
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->once())->method('setCookie');
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