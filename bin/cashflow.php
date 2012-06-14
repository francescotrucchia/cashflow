<?php

require_once __DIR__.'/../../../autoload.php';

use Cashflow\Cashflow;
use Cashflow\Outcome;
use Cashflow\Income;
use Cashflow\Output\Formatter;

if(!file_exists($argv[1])){
  die("File {$argv[1]} does not exists\n");
  
}

$entries = require_once($argv[1]);

$cashflow = new Cashflow;

foreach($entries as $entry){
  $flow = $entry[0];
  array_shift($entry);
  $flow->fromArray($entry);
  $cashflow->add($flow);
}

$formatter = new Formatter($cashflow);
echo $formatter;

