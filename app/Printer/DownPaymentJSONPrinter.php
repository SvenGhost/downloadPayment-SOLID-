<?php
    declare(strict_types=1);
    namespace App\Printer;

    use App\Printer\DownPaymentPrinter;
    use App\Calculator\DownPaymentCalculator;

    class DownPaymentJSONPrinter implements DownPaymentPrinter
    {
        public function print(DownPaymentCalculator $calculator) {
            $value = $calculator->calculate();

            $data = [
                'productName' => $value['productName'],
                'basePriceNet' => $value['basePriceNet'],
                'workingPriceNet' => $value['workingPriceNet'],
            ];

            foreach ($value['monthlyPayments'] as $month => $monthlyPayment) {
                $data['downPayment'][$month] = $monthlyPayment;
            }

            echo(json_encode($data));
        }
    }