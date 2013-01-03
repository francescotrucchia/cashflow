<?php
/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

class Income extends Flow {

    protected $name = 'Income';
    
    public function add(Cashflow $cashflow) {
        $cashflow->addIncoming($this);
    }

    public function calcAmount($amount) {
        return $amount + $this->amount;
    }

    public function getSign() {
        return 1;
    }

}
