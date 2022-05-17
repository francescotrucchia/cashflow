# Cashflow Library

Cashflow library is a set of php classes useful for manage cashflow operation as income, outcome and recurrent entries.

``` php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use Cashflow\Cashflow;
use Cashflow\Outcome;
use Cashflow\Income;

$entries = [
    [new \Cashflow\Income(),  new \DateTime(date('Y/06/10')), 'Balance', 1000],
    [new \Cashflow\Expense(), new \DateTime(date('Y/06/11')), 'Credit card', 100],
    [new \Cashflow\Recurrent(new \Cashflow\Income()),  new \DateTime(date('Y/1/10')), 'Salary', 1500, new \DateInterval('P1M'), new \DateTime(date('Y/12/31'))],
    [new \Cashflow\Recurrent(new \Cashflow\Expense()),  new \DateTime(date('Y/1/12')), 'Rent', 500, new \DateInterval('P1M'), new \DateTime(date('Y/12/31'))],
];

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
        $row->getSign()*$row->getAmount(), 
        $cashflow->getAmount()
    );
}

$output .= PHP_EOL.
  sprintf('Total income: %s'.PHP_EOL, money_format('%.2n', $cashflow->getTotaleIncome())).
  sprintf('Total expense: %s'.PHP_EOL, money_format('%.2n', $cashflow->getTotalExpense())).
  sprintf('Profit: %s'.PHP_EOL, money_format('%.2n', $cashflow->getBalance()));

echo $output;

```
