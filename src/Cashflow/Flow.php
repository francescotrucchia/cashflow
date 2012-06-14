<?php

namespace Cashflow;

use Cashflow\Cashflow;

abstract class Flow implements IFlow{
  
  protected $name;
  protected $amount;
  protected $date;
  
  public function __construct(){
    $this->date = new \DateTime();
  }
  
  public function setName($name){
    $this->name = $name;
  }
  
  public function getName(){
    return $this->name;
  }
  
  public function setAmount($amount){
    $this->amount = $amount;
  }
  
  public function getAmount(){
    return $this->amount;
  }
  
  public function setDate(\DateTime $date){
    $this->date = $date;
  }
  
  public function getDate(){
    return $this->date;
  }
  
  public function __clone(){
    $this->date = clone $this->date;
  }
  
  public function fromArray(array $data){
    $this->setDate($data[0]);
    $this->setName($data[1]);
    $this->setAmount($data[2]);
  }
  
}
