<?php

/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

class Income extends Flow
{
    protected string $name = 'Income';

    public function add(Cashflow $cashflow): void
    {
        $cashflow->addIncoming($this);
    }

    public function calcAmount($amount): float
    {
        return $amount + $this->amount;
    }

    public function getSign(): int
    {
        return 1;
    }
}
