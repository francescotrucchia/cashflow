<?php
/**
 * This file is part of the Cashflow software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cashflow;

use Cashflow\Cashflow;

abstract class Flow implements IFlow {

    protected $id;
    protected $name;
    protected $amount;
    protected $date;

    public function __construct() {
        $this->date = new \DateTime();
    }

    public function __toString(){
        return get_class($this);
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setDate(\DateTime $date) {
        $this->date = $date;
    }

    public function getDate() {
        return $this->date;
    }

    public function __clone() {
        $this->date = clone $this->date;
    }

    public function fromArray(array $data) {
        $this->setDate($data[0]);
        $this->setName($data[1]);
        $this->setAmount($data[2]);
        
        if(isset($data['id'])){
            $this->setId($data['id']);
        }
    }

    public function getType() {
        return get_class($this);
    }

    private function assertDate(Cashflow $cashflow) {          
        if ($this->getDate()->format('U') >= $cashflow->getFrom()->format('U') && $this->getDate()->format('U') <= $cashflow->getTo()->format('U')) {
            return true;
        }

        throw new \Exception('Impossible to import ' . $this->getName() . ' ' . $this->getDate()->format('Y-m-d'));
    }
    
    private function assertNotInItems(Cashflow $cashflow){
        foreach($cashflow->getItems() as $item)
        {
            if(md5("{$this->getId()}{$this->getDate()->format('U')}") == $item->getHash()){
                throw new \Exception('Impossible to import ' . $this->getName() . ' ' . $this->getDate()->format('Y-m-d'));
            }
        }
    }

    public function addToCashflow(Cashflow $cashflow) {
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

    abstract public function getSign();

    abstract protected function add(Cashflow $cashflow);
}
