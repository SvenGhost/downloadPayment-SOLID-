<?php

/** This unit test is just an example*/
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use App\Calculator\DownPaymentCalculator;
use App\Request;

final class Test extends TestCase
{
    public function testCalculate(): void
    {
        $request = new Request();
        $request->setData();
        $calculator = new DownPaymentCalculator($request);
        $result = $calculator->calculate();

        $this->assertSame($result["vat"], 19.0);
        $this->assertSame($result["productName"], "Electricity Simple");
    }
}
