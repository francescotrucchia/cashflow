<?php

namespace Cashflow\Output;

use Cashflow\Cashflow;
use Cashflow\Outcome;
use Cashflow\Income;

class Formatter {

  private $cashflow;
  
  public function __construct(Cashflow $cashflow){
    $this->cashflow = $cashflow;
  }
  
  public function order(){
    $this->cashflow->getEntries()->getIterator()->uasort(function($a, $b){
      if ($a->getDate()->format('U') == $b->getDate()->format('U')) {
        return 0;
      }
      return ($a->getDate()->format('U') < $b->getDate()->format('U')) ? -1 : 1;
    });
  }
  
  public function __toString(){
    
    $this->order();
    
    $mask = "|%-10.10s |%-30.30s |%10s |%10s |%10s |%10s |\n";
    
    $output = sprintf($mask, 'Data', 'Nome', 'Entrate', 'Uscite', 'Stato', 'Con fido');
    
    $status = 0;
    $fido = 1000;
    
    foreach($this->cashflow->getRows() as $row){
      if($row instanceof Income){
        $status = round($status + $row->getAmount(), 2);
        $output .= sprintf($mask, $row->getDate()->format('d/m/y'), $row->getName(), $row->getAmount(), '', $status, $status + $fido);
      }
      
      if($row instanceof Outcome){
        $status = round($status - $row->getAmount(), 2);
        $output .= sprintf($mask, $row->getDate()->format('d/m/y'), $row->getName(), '', $row->getAmount(), $status, $status + $fido);
      }

    }
    
    $output .= "\n";
    $output .= sprintf("Totale entrate: %s\n", $this->cashflow->getIncoming());
    $output .= sprintf("Totale uscite: %s\n", $this->cashflow->getOutcoming());
    $output .= sprintf("Guadagno: %s\n", $this->cashflow->getBalance());
    
    return $output;
  }
}
