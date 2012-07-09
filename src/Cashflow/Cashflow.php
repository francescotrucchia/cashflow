<?php

namespace Cashflow;

class Cashflow{
  
  private $outcoming;
  private $incoming;
  private $entries;
  private $amount = 0;
  private $credit;
  
  public function __construct(){
    $this->outcoming = new Amount();
    $this->incoming = new Amount();
    $this->entries = new Amount();
  }
  
  public function updateAmount($row, $sign){
    $this->amount = round($this->amount + ($sign * $row->getAmount()), 2);
  }
  
  public function getAmount(){
    return $this->amount;
  }
  
  public function setCredit($credit){
    $this->credit = $credit;
  }
  
  public function getCredit(){
    return $this->credit;
  }
  
  public function getCreditAmount(){
    return $this->getAmount() + $this->getCredit();
  }
  
  public function addOutcoming(Outcome $outcome){
    $this->outcoming->add($outcome);
    $this->entries->add($outcome);
  }
  
  public function addIncoming(Income $income){
    $this->incoming->add($income);
    $this->entries->add($income);
  }
  
  public function add(IFlow $flow){
    $flow->addToCashflow($this);
  }
  
  /**
   * @deprecated
   * @return Amount 
   */
  public function getRows(){
    return $this->getEntries();
  }
  
  public function getEntries(){
    return $this->entries;
  }
  
  public function getBalance(){
    return round($this->entries->getAmount(), 2);
  }
  
  public function getIncoming(){
    return $this->incoming->getAmount();
  }
  
  public function getOutcoming(){
    return $this->outcoming->getAmount();
  }

  public function order(){
    $this->getEntries()->getIterator()->uasort(function($a, $b){
      if ($a->getDate()->format('U') == $b->getDate()->format('U')) {
        return 0;
      }
      return ($a->getDate()->format('U') < $b->getDate()->format('U')) ? -1 : 1;
    });
  }
}
