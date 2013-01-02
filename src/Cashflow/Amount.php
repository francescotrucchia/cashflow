<?php

namespace Cashflow;

class Amount extends \ArrayObject{
  private $amount = 0;
  
  public function __construct($array = array()) {
    parent::__construct($array, 0, 'Cashflow\AmountIterator');
    
  }
  
  public function add(Flow $flow){
    $this[] = $flow;
    $this->amount = $flow->calcAmount($this->amount);
  }
  
  public function getAmount(){
    return $this->amount;
  }
  
  public function getRealTimeAmount(){
    return $this->getIterator()->getAmount();
  }
}