<?php 

namespace Nettools\Core\Helpers\Tests;


use \Nettools\Core\Helpers\RequestSecurityHelper\FormToken;
use \Nettools\Core\Helpers\RequestSecurityHelper\ArrayCacheClient;
use \PHPUnit\Framework\TestCase;



class FormTokenTest extends TestCase
{
	function testFormToken()
	{
		$cache = new ArrayCacheClient();
		
		// créer object jeton
		$t = new FormToken($cache);
		
		// vérifier cache vide
		$this->assertEquals(0, count($cache->cache));
		
		// créer le jeton
		$tok = $t->create();
		
		// vérifier qu'il est renvoyé et stocké dans le client
		$this->assertEquals(32+64, strlen($tok));
		$this->assertEquals(1, count($cache->cache));
		
		
		// vérifier jeton
		$this->assertEquals(true, $t->check($tok));
		
		// vérifier cache vide après vérification jeton
		$this->assertEquals(0, count($cache->cache));
	}
	

	function testFormTokenKo()
	{
		$cache = new ArrayCacheClient();
		
		// créer object jeton
		$t = new FormToken($cache);
		
		// créer le jeton
		$tok = $t->create();
		$this->assertEquals(false, $t->check('kkk'));
		
		// modifier jeton dans client
		$k = array_keys($cache->cache)[0];
		$this->assertEquals(32, strlen($k));
		
		$cache->cache[$k] = 'xxx';
		$this->assertEquals(false, $t->check($tok));	
		
		// vérifier cache vide après vérification jeton
		$this->assertEquals(0, count($cache->cache));
	}
	

	function testFormTokenDeleted()
	{
		$cache = new ArrayCacheClient();
		
		// créer object jeton
		$t = new FormToken($cache);
		
		// créer le jeton
		$tok = $t->create();
		$this->assertEquals(1, count($cache->cache));
		
		// supprimer stockage client
		$cache->cache = array();
		
		// la vérif doit échouer
		$this->assertEquals(false, $t->check($tok));	
	}
}

?>