<?php

/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

class Recurrent implements IFlow
{
    private $interval;
    private $dateEnd;
    private $entry;

    public function __construct(Flow $entry)
    {
        $this->entry = $entry;
    }

    public function __toString(): string
    {
        return get_class($this->entry);
    }

    public function getId(): string
    {
        return $this->entry->getId();
    }

    public function setId(string $id): void
    {
        $this->entry->setId($id);
    }

    public function setInterval(\DateInterval $interval): void
    {
        $this->interval = $interval;
    }

    public function setDateEnd(\DateTime $date): void
    {
        $this->dateEnd = $date;
    }

    public function setName(string $name): void
    {
        $this->entry->setName($name);
    }

    public function setDate(\DateTime $date): void
    {
        $this->entry->setDate($date);
    }

    public function setAmount(float $amount): void
    {
        $this->entry->setAmount($amount);
    }

    public function getDate(): \DateTime
    {
        return $this->entry->getDate();
    }

    public function getAmount(): float
    {
        return $this->entry->getAmount();
    }

    public function getSign(): int
    {
        return $this->entry->getSign();
    }

    public function fromArray(array $data): void
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

    public function addToCashflow(Cashflow $cashflow): void
    {
        $entry = $this->entry;

        while ($entry->getDate()->format('U') <= $this->dateEnd->format('U')) {
            $entry->addToCashflow($cashflow);
            $entry = clone $entry;
            $entry->getDate()->add($this->interval);
        }
    }
}
