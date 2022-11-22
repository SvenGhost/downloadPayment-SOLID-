<?php
    declare(strict_types=1);
    namespace App\Printer;

    use App\Calculator\DownPaymentCalculator;

    interface DownPaymentPrinter
    {
        public function print(DownPaymentCalculator $calculator);
    }