<?php

namespace Cashflow;

class Amount extends \ArrayObject{
  private $amount = 0;
  
  public function add(Flow $flow){
    $this[] = $flow;
    $this->amount = $flow->calcAmount($this->amount);
  }
  
  public function getAmount(){
    return $this->amount;
  }
}