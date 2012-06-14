<?php

namespace Cashflow;

use Cashflow\Cashflow;
use Cashflow\Flow;
use Cashflow\Income;
use Cashflow\Outcome;
use Cashflow\IFlow;

class Recurrent implements IFlow{
  
  private $interval;
  private $dateEnd;
  private $entry;
  
  public function __construct(Flow $entry){
    $this->entry = $entry;
  }
  
  public function setInterval(\DateInterval $interval){
    $this->interval = $interval;
  }
  
  public function setDateEnd(\DateTime $date){
    $this->dateEnd = $date;
  }
  
  public function setName($name){
    $this->entry->setName($name);
  }
  
  public function setDate(\DateTime $date){
    $this->entry->setDate($date);
  }
  
  public function setAmount($amount){
    $this->entry->setAmount($amount);
  }
  
  public function fromArray(array $data){
    $this->setDate($data[0]);
    $this->setName($data[1]);
    $this->setAmount($data[2]);
    $this->setInterval($data[3]);
    $this->setDateEnd($data[4]);
  }
  
  public function addToCashflow(Cashflow $cashflow){
    
    $interval_days = $this->interval->format('%d');
    
    $interval = $this->entry->getDate()->diff($this->dateEnd);
    $total_interval_days = $interval->format('%a');
    
    $times = ceil($total_interval_days / $interval_days);
    
    $entry = $this->entry;
    $entry->addToCashflow($cashflow);
    
    for($i = 0; $i < $times-1; $i++){
      $entry = clone $entry;
      $entry->getDate()->add($this->interval);
      $entry->addToCashflow($cashflow);
    }
    
  }
}
