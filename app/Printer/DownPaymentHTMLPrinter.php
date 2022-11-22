<?php
    declare(strict_types=1);
    namespace App\Printer;

    use App\Printer\DownPaymentPrinter;
    use App\Calculator\DownPaymentCalculator;

    class DownPaymentHTMLPrinter implements DownPaymentPrinter
    {
        public function print(DownPaymentCalculator $calculator) {
            $value = $calculator->calculate();
            
            $html = "<div>
            <p>Product Name: {$value['productName']}</p>
            <p>Tariff Base Price Net: {$value['basePriceNet']} EUR</p>
            <p>Tariff Working Price Net: {$value['workingPriceNet']} Cent</p>
            </div>";

            $html .= "<div>";
            foreach ($value['monthlyPayments'] as $month => $monthlyPayment) {
                $html .= "<p>Monthly down payment: {$month} - {$monthlyPayment} EUR</p>\n";
            }
            $html .= "</div>";

            echo($html);
        }
    }