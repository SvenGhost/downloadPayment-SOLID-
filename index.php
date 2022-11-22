<?php
    declare(strict_types=1);
    require 'app/bootstrap.php';

    $htmlPrinter = new App\Printer\DownPaymentHTMLPrinter();
    $jsonPrinter = new App\Printer\DownPaymentJSONPrinter();
    
    $request = new App\Request();
    $request->setData();

    $calculator = new App\Calculator\DownPaymentCalculator($request); 

    $htmlPrinter->print($calculator);
    $jsonPrinter->print($calculator);