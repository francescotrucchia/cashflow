<?php

namespace Cashflow\Output;

use Cashflow\Cashflow;
use Cashflow\Income;
use Cashflow\Outcome;

final class WebFormatter
{
    private Cashflow $cashflow;

    public function __constructor(Cashflow $cashflow)
    {
        $this->cashflow = $cashflow;
    }

    private function format($number): string
    {
        return number_format($number, 2, ',', '.');
    }

    public function __toString(): string
    {
        setlocale(LC_MONETARY, 'it_IT');

        $this->cashflow->order();

        $mask = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td>" .
                "<td><span class=\"%s\">%s</span></td><td><em>%s</em></td></tr>";

        $status = 0;
        $fido = 1000;
        $output = '';

        foreach ($this->cashflow->getEntries() as $row) {
            if ($row instanceof Income) {
                $status = round($status + $row->getAmount(), 2);
                $class = $status < 0 ? 'red' : '';
                $output .= sprintf(
                    $mask,
                    $row->getDate()->format('Y/m/d'),
                    $row->getName(),
                    $this->format($row->getAmount()),
                    '',
                    $class,
                    $this->format($status),
                    $this->format($status + $fido)
                );
            }

            if ($row instanceof Outcome) {
                $status = round($status - $row->getAmount(), 2);
                $class = $status < 0 ? 'red' : '';
                $output .= sprintf(
                    $mask,
                    $row->getDate()->format('Y/m/d'),
                    $row->getName(),
                    '',
                    $this->format($row->getAmount()),
                    $class,
                    $this->format($status),
                    $this->format($status + $fido)
                );
            }
        }

        $header = '<thead>' .
            '<tr><th>Data</th><th>Nome</th><th>Entrate</th><th>Uscite</th><th>Stato</th><th>Con fido</th></tr>' .
            '</thead>';


        $output = "<br/><br/><table id=\"cashflow\">$header<tbody>$output</tbody></table><br/><br/>";

        return $output;
    }
}
