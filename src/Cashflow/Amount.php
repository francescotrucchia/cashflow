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

class Amount extends \ArrayObject
{
    private Money $amount;

    public function __construct($array = [])
    {
        parent::__construct($array, 0, AmountIterator::class);
        $this->amount = Money::EUR(0);
    }

    public function add(Flow $flow): void
    {
        $this[] = $flow;
        $this->amount = $flow->calcAmount($this->amount);
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getRealTimeAmount(): ?Money
    {
        return $this->getIterator()->getAmount();
    }
}
