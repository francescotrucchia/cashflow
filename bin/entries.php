<?php

$dateEnd = '2012/12/31';

return array(
    array(new \Cashflow\Income(),  new \DateTime(date('Y/06/10')), 'Saldo', 1000),
    array(new \Cashflow\Outcome(), new \DateTime(date('Y/06/11')), 'Carta di credito', 100),
    array(new \Cashflow\Recurrent(new \Cashflow\Income()),  new \DateTime(date('Y/7/10')), 'Busta paga', 1500, new \DateInterval('P30D'), new \DateTime($dateEnd)),
    array(new \Cashflow\Recurrent(new \Cashflow\Outcome()),  new \DateTime(date('Y/7/20')), 'Affitto', 500, new \DateInterval('P30D'), new \DateTime($dateEnd)),
);
