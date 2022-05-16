<?php

/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

use Money\Money;

/**
 * Cashflow class
 *
 * @author Francesco Trucchia <francesco@trucchia.it>
 */
class Cashflow
{
    private Amount $expense;
    private Amount $income;
    private Amount $entries;
    private Money $amount;
    private Money $credit;
    private \DateTime $from;
    private \DateTime $to;
    private Income $currentBalance;
    private array $items;
    private Flow $min;
    private Flow $max;
    private array $errors = [];
    private CashflowFilter $filteredEntries;

    public function __construct(\DateTime $from, \DateTime $to, array $items = [])
    {
        $this->expense = new Amount();
        $this->income = new Amount();
        $this->entries = new Amount();

        $this->amount = Money::EUR(0);
        $this->credit = Money::EUR(0);

        $this->from = $from;
        $this->to = $to;
        $this->items = $items;

        $this->min = new Income();
        $this->max = new Income();

        $this->filteredEntries = new CashflowFilter($this->getEntries()->getIterator(), $from, $to);
    }

    public function updateAmount(Flow $row, int $sign): void
    {
        $this->amount = $this->amount->add($row->getAmount()->multiply($sign));
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setCredit(Money $credit): void
    {
        $this->credit = $credit;
    }

    public function getCredit(): Money
    {
        return $this->credit;
    }

    public function getCreditAmount(): Money
    {
        return $this->getAmount()->add($this->getCredit());
    }

    public function addOutcoming(Outcome $outcome): void
    {
        $this->expense->add($outcome);
        $this->entries->add($outcome);
    }

    public function addIncoming(Income $income): void
    {
        $this->income->add($income);
        $this->entries->add($income);
    }

    public function add(IFlow $flow): void
    {
        $flow->addToCashflow($this);
    }

    public function getCurrentBalance(): Income
    {
        return $this->currentBalance;
    }

    public function setCurrentBalance(Income $balance): void
    {
        $this->currentBalance = $balance;
        $this->income->add($balance);
    }

    public function getFilteredEntries(): \FilterIterator
    {
        return $this->filteredEntries;
    }

    public function getEntries(): Amount
    {
        return $this->entries;
    }

    public function getBalance(): Money
    {
        return $this->entries->getAmount();
    }

    public function getRealTimeBalance(): Money
    {
        return $this->entries->getRealTimeAmount();
    }

    public function getIncoming(): Money
    {
        return $this->income->getAmount();
    }

    public function getTotaleIncome(): Money
    {
        return $this->income->getAmount();
    }

    public function getRealTimeIncoming(): Money
    {
        return $this->income->getRealTimeAmount();
    }

    public function getOutcoming(): Money
    {
        return $this->expense->getAmount();
    }

    public function getTotalExpense(): Money
    {
        return $this->expense->getAmount();
    }

    public function getRealTimeOutcoming(): Money
    {
        return $this->expense->getRealTimeAmount();
    }

    public function getFrom(): \DateTime
    {
        return $this->from;
    }

    public function getTo(): \DateTime
    {
        return $this->to;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function order(): void
    {
        $this->getEntries()->getIterator()->uasort(
            function ($a, $b) {
                if ($a->getDate()->format('U') == $b->getDate()->format('U')) {
                    return 0;
                }
                    return ($a->getDate()->format('U') < $b->getDate()->format('U')) ? -1 : 1;
            }
        );
    }

    public function import(array $entries): void
    {
        foreach ($entries as $entry) {
            /** @var Flow $flow */
            $flow = $entry[0];
            array_shift($entry);
            $flow->fromArray($entry);
            $this->add($flow);
        }
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getMin(): Flow
    {
        return $this->min;
    }

    public function getMax(): Flow
    {
        return $this->max;
    }

    public function setMin(Flow $entry): void
    {
        if (!$this->min || $this->min->getAmount() > $entry->getAmount()) {
            $this->min = $entry;
        }
    }

    public function setMax(Flow $entry): void
    {
        if (!$this->max || $this->max->getAmount() < $entry->getAmount()) {
            $this->max = $entry;
        }
    }
}
