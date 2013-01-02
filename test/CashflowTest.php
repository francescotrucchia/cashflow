<?php

namespace Cashflow\Test;

require_once __DIR__.'/../../autoload.php';

use Cashflow\Cashflow;
use Cashflow\Output\Formatter;
use Cashflow\Outcome;
use Cashflow\Income;
use Cashflow\Recurrent;
use Cashflow\CashflowFilter;

class CashflowTest extends \PHPUnit_Framework_TestCase{
  
  private $cashflow;
  
  public function setup(){
    $this->cashflow = new Cashflow(new \DateTime(date('Y/1/1')), new \DateTime(date('Y/12/30')));
  }
  
  public function testInstance(){
    
    $sale = new Income();
    $sale->fromArray(array(new \DateTime(date('Y/m/d')), 'Sale', 43.22));
    
    $purchase = new Outcome();
    $purchase->fromArray(array(new \DateTime(date('Y/m/d')), 'Purchase', 98.34));
    
    $this->cashflow->add($sale);
    $this->cashflow->add($purchase);
    
    $this->assertEquals(-55.12, $this->cashflow->getBalance());
    $this->assertEquals(43.22, $this->cashflow->getIncoming());
    $this->assertEquals(-98.34, $this->cashflow->getOutcoming());
  }
  
  public function testRecurrent(){
    
    $income = new Income();
    $income->fromArray(array(new \DateTime(date('Y/1/10')), 'Income', 100));
    
    $recurrent = new Recurrent($income);
    $recurrent->setInterval(new \DateInterval('P1M'));
    $recurrent->setDateEnd(new \DateTime(date('Y/1/10', strtotime('+1 years'))));
    
    $this->cashflow->add($recurrent);
    
    $this->assertEquals(12, $this->cashflow->getRows()->count());
  }
  
  public function testPeriod(){
    
    $income = new Income();
    $income->setAmount(100);
    $income->setDate(new \DateTime(date('Y/m/10')));
    
    $recurrent = new Recurrent($income);
    $recurrent->setInterval(new \DateInterval('P30D'));
    $recurrent->setDateEnd(new \DateTime(date('Y/m/10', strtotime('+10 months'))));
    
    $this->cashflow->add($recurrent);
    
    $filter = new CashflowFilter($this->cashflow->getRows()->getIterator(), new \DateTime(date('Y/m/1')), new \DateTime(date('Y/m/10', strtotime('+5 months'))));
    
    $count = 0;
    foreach($filter as $result){
      $count++;
    }
    
    $this->assertEquals(6, $count);
  }

}
