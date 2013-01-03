<?php

require_once __DIR__.'/../src/Cashflow/Autoload.php';

$loader = new \Cashflow\ClassLoader('Cashflow', __DIR__ . '/../src');
$loader->register();

use Cashflow\Cashflow;
use Cashflow\Outcome;
use Cashflow\Income;

$entries = array(
    array(new \Cashflow\Income(),  new \DateTime(date('Y/06/10')), 'Balance', 1000),
    array(new \Cashflow\Outcome(), new \DateTime(date('Y/06/11')), 'Credit card', 100),
    array(new \Cashflow\Recurrent(new \Cashflow\Income()),  new \DateTime(date('Y/1/10')), 'Salary', 1500, new \DateInterval('P1M'), new \DateTime(date('Y/12/31'))),
    array(new \Cashflow\Recurrent(new \Cashflow\Outcome()),  new \DateTime(date('Y/1/12')), 'Rent', 500, new \DateInterval('P1M'), new \DateTime(date('Y/12/31'))),
);

$cashflow = new Cashflow(new \DateTime(date('Y/1/1')), new \DateTime(date('Y/12/30')));
$cashflow->import($entries);
$cashflow->order();
    
$mask = "|%-10.10s |%-30.30s |%15s |%15s |\n";

$output = sprintf($mask, 'Date', 'Name', 'Flow', 'Balance');

foreach($cashflow->getRows() as $row){
    $cashflow->updateAmount($row, $row->getSign());
    $output .= sprintf(
        $mask, 
        $row->getDate()->format('Y-m-d'), 
        $row->getName(), 
        money_format('%.2n', $row->getSign()*$row->getAmount()), 
        money_format('%.2n', $cashflow->getAmount())
    );
}

$output .= PHP_EOL.
  sprintf('Totale entrate: %s'.PHP_EOL, money_format('%.2n', $cashflow->getIncoming())).
  sprintf('Totale uscite: %s'.PHP_EOL, money_format('%.2n', $cashflow->getOutcoming())).
  sprintf('Guadagno: %s'.PHP_EOL, money_format('%.2n', $cashflow->getBalance()));

echo $output;