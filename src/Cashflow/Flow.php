<?php

/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

abstract class Flow implements IFlow
{
    protected string $id;
    protected string $name;
    protected float $amount;
    protected \DateTime $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function __toString(): string
    {
        return get_class($this);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function __clone()
    {
        $this->date = clone $this->date;
    }

    public function fromArray(array $data): void
    {
        $this->setDate($data[0]);
        $this->setName($data[1]);
        $this->setAmount($data[2]);

        if (isset($data['id'])) {
            $this->setId($data['id']);
        }
    }

    public function getType(): string
    {
        return get_class($this);
    }

    private function assertDate(Cashflow $cashflow): bool
    {
        if (
            $this->getDate()->format('U') >= $cashflow->getFrom()->format('U') &&
            $this->getDate()->format('U') <= $cashflow->getTo()->format('U')
        ) {
            return true;
        }

        throw new \Exception(sprintf(
            'Impossible to import %s %s',
            $this->getName(),
            $this->getDate()->format('Y-m-d')
        ));
    }

    private function assertNotInItems(Cashflow $cashflow): void
    {
        foreach ($cashflow->getItems() as $item) {
            if (md5("{$this->getId()}{$this->getDate()->format('U')}") == $item->getHash()) {
                throw new \Exception(sprintf(
                    'Impossible to import %s %s',
                    $this->getName(),
                    $this->getDate()->format('Y-m-d')
                ));
            }
        }
    }

    public function addToCashflow(Cashflow $cashflow): void
    {
        try {
            $this->assertDate($cashflow);
            $this->assertNotInItems($cashflow);
            $this->add($cashflow);
            $cashflow->setMin($this);
            $cashflow->setMax($this);
        } catch (\Exception $e) {
            $cashflow->addError($e->getMessage());
        }
    }

    abstract public function getSign(): int;

    abstract protected function add(Cashflow $cashflow): void;
}
