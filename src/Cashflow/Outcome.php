<?php

namespace Cashflow;

class Outcome extends Flow {

    protected $name = 'Outcome';

    public function add(Cashflow $cashflow) {
        $cashflow->addOutcoming($this);
    }

    public function calcAmount($amount) {
        return $amount - $this->amount;
    }

    public function getSign() {
        return -1;
    }

}
