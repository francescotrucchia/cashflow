<?php

require_once __DIR__.'/../../../autoload.php';

use Cashflow\Cashflow;
use Cashflow\Outcome;
use Cashflow\Income;
use Cashflow\Output\Formatter;

if(!file_exists($argv[1])){
  die("File {$argv[1]} does not exists\n");
  
}

$entries = require_once($argv[1]);

$cashflow = new Cashflow;
$cashflow->setCredit(1000);

foreach($entries as $entry){
  $flow = $entry[0];
  array_shift($entry);
  $flow->fromArray($entry);
  $cashflow->add($flow);
  
  
}

$cashflow->order();
    
$mask = "|%-10.10s |%-30.30s |%10s |%10s |%10s |%10s |\n";

$output = sprintf($mask, 'Data', 'Nome', 'Entrate', 'Uscite', 'Stato', 'Con fido');

foreach($cashflow->getRows() as $row){
  $cashflow->updateAmount($row, $row->getSign());

  if($row instanceof Income){
    $output .= sprintf($mask, $row->getDate()->format('d/m/y'), $row->getName(), $row->getAmount(), '', $cashflow->getAmount(), $cashflow->getCreditAmount());
  }

  if($row instanceof Outcome){
    $output .= sprintf($mask, $row->getDate()->format('d/m/y'), $row->getName(), '', $row->getAmount(), $cashflow->getAmount(), $cashflow->getCreditAmount());
  }

}

$output .= "\n";
$output .= sprintf("Totale entrate: %s\n", $cashflow->getIncoming());
$output .= sprintf("Totale uscite: %s\n", $cashflow->getOutcoming());
$output .= sprintf("Guadagno: %s\n", $cashflow->getBalance());

echo $output;

