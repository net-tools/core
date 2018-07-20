<?php


namespace Nettools\Core\Misc\Tests;



class PdoConfigTest extends \PHPUnit\Framework\TestCase
{
	
	public function testPdoConfig()
	{
		$qst_stub = $this->createMock(\PDOStatement::class);
		$qst_stub->method('execute')->with($this->equalTo(['my_value']))->willReturn(true);
		$qst_stub->method('fetchColumn')->with($this->equalTo(0))->willReturn('myvalue');
		
		$cfg = new \Nettools\Core\Misc\PdoConfig($qst_stub);
		
		$this->assertEquals('myvalue', $cfg->my_value);
	}
	
	
	
	public function testPdoConfigPrefix()
	{
		$qst_stub = $this->createMock(\PDOStatement::class);
		$qst_stub->method('execute')->with($this->equalTo(['bank_my_value']))->willReturn(true);
		$qst_stub->method('fetchColumn')->with($this->equalTo(0))->willReturn('myvalue_from_bank');
		
		$cfg = new \Nettools\Core\Misc\PdoConfig($qst_stub, 'bank_');
		
		$this->assertEquals('myvalue_from_bank', $cfg->my_value);
	}
	
	
	
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Config value 'novalue' does not exist
     */
    public function testInexistantValue()
    {
		$qst_stub = $this->createMock(\PDOStatement::class);
		$qst_stub->method('execute')->with($this->equalTo(['novalue']))->willReturn(true);
		$qst_stub->method('fetchColumn')->with($this->equalTo(0))->willReturn(FALSE);
		
		$cfg = new \Nettools\Core\Misc\PdoConfig($qst_stub);
		$cfg->novalue;
    }
	
	
	
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Config value 'sqlerr' can't be read (SQL error : unknown error) 
     */
    public function testSqlError()
    {
		$qst_stub = $this->createMock(\PDOStatement::class);
		$qst_stub->method('execute')->with($this->equalTo(['sqlerr']))->will($this->throwException(new \PDOException('unknown error')));
		
		$cfg = new \Nettools\Core\Misc\PdoConfig($qst_stub);
		$cfg->sqlerr;
    }
    
	
	
}



?>
