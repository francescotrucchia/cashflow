<?php

namespace Cashflow;

class Income extends Flow{
  protected $name = 'Income';
  
  public function addToCashflow(Cashflow $cashflow){
    $cashflow->addIncoming($this);
  }
  
  public function calcAmount($amount){
    return $amount + $this->amount;
  }
}
