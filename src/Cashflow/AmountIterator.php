<?php

namespace Cashflow;

class AmountIterator extends \ArrayIterator{
  
  private $amount = 0;
  
  public function current(){
    $flow = parent::current();
    $this->amount = $flow->calcAmount($this->amount);
    
    return $flow;
  }
  
  public function getAmount(){
    return $this->amount;
  }
}
