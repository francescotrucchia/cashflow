<?php

namespace Cashflow\Test;

use Cashflow\Cashflow;
use Cashflow\Expense;
use Cashflow\Income;
use Cashflow\Recurrent;
use Money\Money;
use PHPUnit\Framework\TestCase;

class CashflowTest extends TestCase
{
    private $cashflow;

    public function setup(): void
    {
        $this->cashflow = new Cashflow(
            new \DateTime(date('Y/1/1')),
            new \DateTime(date('Y/12/30', strtotime('+1 year')))
        );
    }

    public function testInstance(): void
    {

        $sale = new Income();
        $sale->fromArray(array(new \DateTime(date('Y/m/d')), 'Sale', Money::EUR(4322)));

        $purchase = new Expense();
        $purchase->fromArray(array(new \DateTime(date('Y/m/d')), 'Purchase', Money::EUR(9834)));

        $this->cashflow->add($sale);
        $this->cashflow->add($purchase);

        $this->assertEquals(-5512, $this->cashflow->getBalance()->getAmount());
        $this->assertEquals(4322, $this->cashflow->getIncoming()->getAmount());
        $this->assertEquals(-9834, $this->cashflow->getOutcoming()->getAmount());
        $this->assertEquals(4322, $this->cashflow->getRealTimeIncoming()->getAmount());
        $this->assertEquals(-9834, $this->cashflow->getRealTimeOutcoming()->getAmount());
        $this->assertEquals(4322, $this->cashflow->getRealTimeBalance()->getAmount());
    }

    public function testRecurrent(): void
    {

        $income = new Income();
        $income->fromArray(array(new \DateTime(date('Y/1/10')), 'Income', Money::EUR(10000)));

        $recurrent = new Recurrent($income);
        $recurrent->setInterval(new \DateInterval('P1M'));
        $recurrent->setDateEnd(new \DateTime(date('Y/12/30')));

        $this->cashflow->add($recurrent);

        $this->assertEquals(12, $this->cashflow->getEntries()->count());
    }

    public function testPeriod(): void
    {

        $cashflow = new Cashflow(new \DateTime(date('Y/1/1')), new \DateTime(date('Y/5/31')));

        $income = new Income();
        $income->setAmount(Money::EUR(10000));
        $income->setDate(new \DateTime(date('Y/1/10')));

        $recurrent = new Recurrent($income);
        $recurrent->setInterval(new \DateInterval('P1M'));
        $recurrent->setDateEnd(new \DateTime(date('Y/12/30')));

        $cashflow->add($recurrent);


        $count = 0;
        foreach ($cashflow->getFilteredEntries() as $result) {
            $count++;
        }

        $this->assertEquals(5, $count);
    }
}
