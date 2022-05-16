<?php

/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

/**
 * Cashflow class
 *
 * @author Francesco Trucchia <francesco@trucchia.it>
 */
class Cashflow
{
    private $expense;
    private $income;
    private $entries;
    private $amount = 0;
    private $credit;
    private $from;
    private $to;
    private $current_balance;
    private $items;
    private $min;
    private $max;
    private $errors = array();

    public function __construct(\DateTime $from, \DateTime $to, $items = array())
    {
        $this->expense = new Amount();
        $this->income = new Amount();
        $this->entries = new Amount();

        $this->from = $from;
        $this->to = $to;
        $this->items = $items;

        $this->filteredEntries = new CashflowFilter($this->getEntries()->getIterator(), $from, $to);
    }

    public function updateAmount($row, $sign)
    {
        $this->amount = round($this->amount + ($sign * $row->getAmount()), 2);
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCredit($credit)
    {
        $this->credit = $credit;
    }

    public function getCredit()
    {
        return $this->credit;
    }

    public function getCreditAmount()
    {
        return $this->getAmount() + $this->getCredit();
    }

    public function addOutcoming(Outcome $outcome)
    {
        $this->expense->add($outcome);
        $this->entries->add($outcome);
    }

    public function addIncoming(Income $income)
    {
        $this->income->add($income);
        $this->entries->add($income);
    }

    public function add(IFlow $flow)
    {
        $flow->addToCashflow($this);
    }

    public function getCurrentBalance()
    {
        return $this->current_balance;
    }

    public function setCurrentBalance(Income $balance)
    {
        $this->current_balance = $balance;
        $this->income->add($balance);
    }

    /**
     * @deprecated use getEntries
     * @return     Amount
     */
    public function getRows()
    {
        return $this->getEntries();
    }

    public function getFilteredEntries()
    {
        return $this->filteredEntries;
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function getBalance()
    {
        return round($this->entries->getAmount(), 2);
    }

    public function getRealTimeBalance()
    {
        return round($this->entries->getRealTimeAmount(), 2);
    }

    public function getIncoming()
    {
        return $this->income->getAmount();
    }

    public function getTotaleIncome()
    {
        return $this->income->getAmount();
    }

    public function getRealTimeIncoming()
    {
        return $this->income->getRealTimeAmount();
    }

    public function getOutcoming()
    {
        return $this->expense->getAmount();
    }

    public function getTotalExpense()
    {
        return $this->expense->getAmount();
    }

    public function getRealTimeOutcoming()
    {
        return $this->expense->getRealTimeAmount();
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function order()
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

    public function import(array $entries)
    {
        foreach ($entries as $entry) {
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

    public function getErrors()
    {
        return $this->errors;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function getMax()
    {
        return $this->max;
    }

    public function setMin($entry)
    {
        if (!$this->min || $this->min->getAmount() > $entry->getAmount()) {
            $this->min = $entry;
        }
    }

    public function setMax($entry)
    {
        if (!$this->max || $this->max->getAmount() < $entry->getAmount()) {
            $this->max = $entry;
        }
    }
}
