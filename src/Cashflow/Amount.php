<?php

/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

class Amount extends \ArrayObject
{
    private float $amount = 0;

    public function __construct($array = [])
    {
        parent::__construct($array, 0, AmountIterator::class);
    }

    public function add(Flow $flow): void
    {
        $this[] = $flow;
        $this->amount = $flow->calcAmount($this->amount);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getRealTimeAmount(): float
    {
        return $this->getIterator()->getAmount();
    }
}
