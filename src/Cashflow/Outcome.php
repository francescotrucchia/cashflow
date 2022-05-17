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

class Outcome extends Flow
{
    protected string $name = 'Outcome';

    public function add(Cashflow $cashflow): void
    {
        $cashflow->addOutcoming($this);
    }

    public function calcAmount(Money $amount): Money
    {
        return $amount->subtract($this->amount);
    }

    public function getSign(): int
    {
        return -1;
    }
}
