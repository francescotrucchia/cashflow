<?php

namespace Cashflow;

class Outcome extends Flow{
  protected $name = 'Outcome';
  
  public function addToCashflow(Cashflow $cashflow){
    $cashflow->addOutcoming($this);
  }
  
  public function calcAmount($amount){
    return $amount - $this->amount;
  }
}
