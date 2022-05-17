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

class AmountIterator extends \ArrayIterator
{
    private Money $amount;

    public function __construct($array = [], $flags = 0)
    {
        $this->amount = Money::EUR(0);
        parent::__construct($array, $flags);
    }

    public function current(): mixed
    {
        $this->amount = Money::EUR(0);

        /** @var Flow $flow */
        $flow = parent::current();
        $this->amount = $flow->calcAmount($this->amount);

        return $flow;
    }

    public function getAmount(): Money
    {
        $this->current();
        return $this->amount;
    }
}
