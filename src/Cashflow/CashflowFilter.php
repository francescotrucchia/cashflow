<?php
/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

