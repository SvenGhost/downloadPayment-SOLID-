<?php
    declare(strict_types=1);
    namespace App;

    class Request
    {
        public $postalCode; // coming from customer
        public $yearlyUsage; // coming from customer
        public $vat; // coming from customer
        public $downPaymentInterval; // system setting
        public $product; // system settings
        public $bonus; // system settings

        public function setData() {
            $this->postalCode = '10789';
            $this->vat = 19.00;
            $this->yearlyUsage = 3500;
            $this->downPaymentInterval = 12;
            $this->product = [
                [
                    'name' => 'Electricity Simple',
                    'validFrom' => '2021-01-01',
                    'validUntil' => '2022-12-31',
                    'tariff' => [
                        [
                            'name' => 'Tariff 1',
                            'usageFrom' => 0,
                            'validFrom' => '2021-01-01',
                            'validUntil' => '2021-12-31',
                            'workingPriceNet' => 0.20,
                            'basePriceNet' => 50.00
                        ],
                        [
                            'name' => 'Tariff 2',
                            'usageFrom' => 2,
                            'validFrom' => '2022-01-01',
                            'validUntil' => '2022-12-31',
                            'workingPriceNet' => 0.20,
                            'basePriceNet' => 50.00
                        ],
                        [
                            'name' => 'Tariff 3',
                            'usageFrom' => 3001,
                            'validFrom' => '2022-01-01',
                            'validUntil' => '2022-12-31',
                            'workingPriceNet' => 0.15,
                            'basePriceNet' => 40.00
                        ],
                        [
                            'name' => 'Tariff 4',
                            'usageFrom' => 5001,
                            'validFrom' => '2022-01-01',
                            'validUntil' => '2022-12-31',
                            'workingPriceNet' => 0.12,
                            'basePriceNet' => 35.00
                        ]
                    ]
                ]
            ];
            $this->bonus = [
                [
                    'name' => 'BONUS-A',
                    'usageFrom' => 0,
                    'validFrom' => '2021-01-01',
                    'validUntil' => '2022-12-31',
                    'value' => 5,
                    'paymentAfterMonths' => 0
                ],
                [
                    'name' => 'BONUS-B',
                    'usageFrom' => 0,
                    'validFrom' => '2021-01-01',
                    'validUntil' => '2022-12-31',
                    'value' => 5,
                    'paymentAfterMonths' => 6
                ],
                [
                    'name' => 'BONUS-C',
                    'usageFrom' => 2500,
                    'validFrom' => '2021-01-01',
                    'validUntil' => '2022-12-31',
                    'value' => 2.5,
                    'paymentAfterMonths' => 3
                ],
                [
                    'name' => 'BONUS-D',
                    'usageFrom' => 4500,
                    'validFrom' => '2021-01-01',
                    'validUntil' => '2022-12-31',
                    'value' => 1.25,
                    'paymentAfterMonths' => 9
                ]
            ];
        }
        
    }