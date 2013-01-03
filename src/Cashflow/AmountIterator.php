<?php
/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
