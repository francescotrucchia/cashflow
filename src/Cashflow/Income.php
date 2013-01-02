<?php

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
