<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cashflow\Cashflow;
use Cashflow\Expense;
use Cashflow\Income;
use Cashflow\MoneyUtils;
use Cashflow\Recurrent;
use Money\Money;

$entries = array(
    array(new Income(), new \DateTime(date('Y/06/10')), 'Balance', Money::EUR(100000)),
    array(new Expense(), new \DateTime(date('Y/06/11')), 'Credit card', Money::EUR(10000)),
    array(new Recurrent(new Income()), new \DateTime(date('Y/1/10')), 'Salary', Money::EUR(150000), new \DateInterval('P1M'), new \DateTime(date('Y/12/31', strtotime('+1 years')))),
    array(new Recurrent(new Expense()), new \DateTime(date('Y/1/12')), 'Rent', Money::EUR(50000), new \DateInterval('P1M'), new \DateTime(date('Y/12/31', strtotime('+1 years')))),
);

$cashflow = new Cashflow(new \DateTime(date('Y/1/1')), new \DateTime(date('Y/1/1', strtotime('+1 years'))));
$cashflow->import($entries);
$cashflow->order();

$mask = "|%-10.10s |%-30.30s |%15s |%15s |\n";

$output = sprintf($mask, 'Date', 'Name', 'Flow', 'Balance');

foreach ($cashflow->getEntries() as $row) {
    $cashflow->updateAmount($row, $row->getSign());
    $output .= sprintf(
        $mask,
        $row->getDate()->format('Y-m-d'),
        $row->getName(),
        MoneyUtils::toFloat($row->getAmount()->multiply($row->getSign())),
        MoneyUtils::toFloat($cashflow->getAmount())
    );
}

$output .= PHP_EOL .
    sprintf('Total income: %s' . PHP_EOL, MoneyUtils::toFloat($cashflow->getTotaleIncome())) .
    sprintf('Total expense: %s' . PHP_EOL, MoneyUtils::toFloat($cashflow->getTotalExpense())) .
    sprintf('Profit: %s' . PHP_EOL, MoneyUtils::toFloat($cashflow->getBalance()));

echo $output;