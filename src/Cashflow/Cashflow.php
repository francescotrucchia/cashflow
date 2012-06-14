<?php

namespace Cashflow;

class Cashflow {
  
  private $outcoming;
  private $incoming;
  private $entries;
  
  public function __construct(){
    $this->outcoming = new Amount();
    $this->incoming = new Amount();
    $this->entries = new Amount();
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

  public function setFrom(\DateTime $date){
    $this->from = $date;
  }
  
  public function setTo(\DateTime $to){
    $this->to = $to;
  }
}
