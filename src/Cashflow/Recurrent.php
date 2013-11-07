<?php
/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

use Cashflow\Cashflow;
use Cashflow\Flow;
use Cashflow\Income;
use Cashflow\Outcome;
use Cashflow\IFlow;

class Recurrent implements IFlow
{

    private $interval;
    private $dateEnd;
    private $entry;

    public function __construct(Flow $entry)
    {
        $this->entry = $entry;
    }

    public function __toString()
    {
        return get_class($this->entry);
    }

    public function getId()
    {
        return $this->entry->getId();
    }

    public function setId($id)
    {
        $this->entry->setId($id);
    }

    public function setInterval(\DateInterval $interval)
    {
        $this->interval = $interval;
    }

    public function setDateEnd(\DateTime $date)
    {
        $this->dateEnd = $date;
    }

    public function setName($name)
    {
        $this->entry->setName($name);
    }

    public function setDate(\DateTime $date)
    {
        $this->entry->setDate($date);
    }

    public function setAmount($amount)
    {
        $this->entry->setAmount($amount);
    }

    public function getDate()
    {
        return $this->entry->getDate();
    }

    public function getAmount()
    {
        return $this->entry->getAmount();
    }

    public function getSign()
    {
        return $this->entry->getSign();
    }
    
    public function fromArray(array $data)
    {
        $this->setDate($data[0]);
        $this->setName($data[1]);
        $this->setAmount($data[2]);
        $this->setInterval($data[3]);
        $this->setDateEnd($data[4]);

        if (isset($data['id'])) {
            $this->setId($data['id']);
        }
    }

    public function addToCashflow(Cashflow $cashflow)
    {
        $entry = $this->entry;
        
        while ($entry->getDate()->format('U') <= $this->dateEnd->format('U')) {
            $entry->addToCashflow($cashflow);
            $entry = clone $entry;
            $entry->getDate()->add($this->interval);
        }
    }

}
