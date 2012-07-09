<?php

namespace Cashflow;

class CashflowFilter extends \FilterIterator{
  
  private $from;
  private $to;
  
  public function __construct(\Iterator $iterator , \DateTime $from, \DateTime $to)
  {
      parent::__construct($iterator);
      $this->from = $from;
      $this->to = $to;
  }

  public function accept()
  {
      $entry = $this->getInnerIterator()->current();
      
      if($entry->getDate()->format('U') >= $this->from->format('U') && $entry->getDate()->format('U') <= $this->to->format('U')) {
          return true;
      }        
      return false;
  }
}

