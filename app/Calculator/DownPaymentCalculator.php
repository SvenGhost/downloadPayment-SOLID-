<?php
    declare(strict_types=1);
    namespace App\Calculator;

    use App\Request;

    class DownPaymentCalculator
    {
        private $request;

        public function __construct(Request $request) {
            $this->request = $request;
        }

        private function validateDate($date) {
            if (preg_match("/^(20[0-9]{2})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
                return true;
            } else {
                return false;
            }
        }

        public function calculate() {
            $data = [];

            $now = new \DateTime('now');

            if (empty($this->request->postalCode)) {
                echo 'Zip code is missing';
                exit;
            }

            if (!is_numeric($this->request->vat)
                || (float)$this->request->vat > 100 
                || (float)$this->request->vat < 0) {
                echo 'Vat is missing or invalid';
                exit;
            }

            if (empty($this->request->downPaymentInterval)
                || !is_numeric($this->request->downPaymentInterval)
                || (int)$this->request->downPaymentInterval < 1) {
                echo 'Down payment interval is missing or invalid';
                exit;
            }

            if (empty($this->request->yearlyUsage)
                || !is_numeric($this->request->yearlyUsage)
                || (int)$this->request->yearlyUsage < 0) {
                echo 'Yearly usage is missing or invalid';
                exit;
            }

            if (empty($this->request->product)) {
                echo 'Products are missing';
                exit;
            }

            $data['yearlyUsage'] = $this->request->yearlyUsage;
            $data['vat'] = $this->request->vat;
            $data['downPaymentInterval'] = $this->request->downPaymentInterval;

            foreach ($this->request->product as $product) {
                //validation for porducts
                if (empty($product['name']) 
                    || empty($product['validFrom']) 
                    || empty($product['validUntil'])) {
                    echo 'Some products informations are missing';
                    exit;
                }

                if (!$this->validateDate($product['validFrom']) 
                    || !$this->validateDate($product['validUntil'])) {
                    echo 'Some products dates are wrong';  
                    exit;
                }

                if ($now >= new \DateTime($product['validFrom']) && $now <= new \DateTime($product['validUntil'])) {
                    $data['productName'] = $product['name'];
                    foreach ($product['tariff'] as $tariff) {
                        //validation for tariff
                        if (empty($tariff['name']) 
                            || !is_numeric($tariff['usageFrom']) 
                            || empty($tariff['validFrom']) 
                            || empty($tariff['validUntil']) 
                            || !is_numeric($tariff['workingPriceNet']) 
                            || !is_numeric($tariff['basePriceNet'])) {
                            echo 'Some tariff informations are missing';
                            exit;
                        }

                        if ($tariff['usageFrom'] < 0 
                            || $tariff['workingPriceNet'] < 0 
                            || $tariff['basePriceNet'] < 0) {
                            echo 'Some tariff values are wrong';
                            exit;
                        }
                        
                        if (!$this->validateDate($tariff['validFrom']) 
                            || !$this->validateDate($tariff['validUntil'])) {
                            echo 'Some tariff dates are wrong';  
                            exit;
                        }

                        if ($now >= new \DateTime($tariff['validFrom']) 
                            && $now <= new \DateTime($tariff['validUntil'])
                            && $this->request->yearlyUsage >= $tariff['usageFrom']) {
                            $data['tariff'] = $tariff;
                            $data['workingPriceNet'] = $tariff['workingPriceNet'];
                            $data['basePriceNet'] = $tariff['basePriceNet'];
                        }
                    }
                }
            }

            if (empty($data['tariff'])) {
                echo 'No tariff selected';
                exit;
            }

            // check for valid bonus
            foreach ($this->request->bonus as $bonus) {
                //validation for bonus
                if (empty($bonus['name']) 
                    || !is_numeric($bonus['usageFrom']) 
                    || empty($bonus['validFrom']) 
                    || empty($bonus['validUntil']) 
                    || !is_numeric($bonus['value']) 
                    || !is_numeric($bonus['paymentAfterMonths'])) {
                    echo 'Some bonus informations are missing';
                    exit;
                }

                if ($bonus['usageFrom'] < 0 
                    || $bonus['value'] < 0 
                    || $bonus['paymentAfterMonths'] < 0) {
                    echo 'Some bonus values are wrong';
                    exit;
                }
                
                if (!$this->validateDate($bonus['validFrom']) 
                    || !$this->validateDate($bonus['validUntil'])) {
                    echo 'Some bonus dates are wrong';  
                    exit;
                }


                if ($now >= new \DateTime($bonus['validFrom']) && $now <= new \DateTime($bonus['validUntil'])
                    && $this->request->yearlyUsage >= $bonus['usageFrom']) {
                    $data['bonus'][] = $bonus;
                }
            }

            // yearly working price
            $data['workingPriceNetYearly'] = $data['workingPriceNet'] * $data['yearlyUsage'];

            // calculate monthly down payment for the contract
            $data['monthlyDownPayment'] = ($data['basePriceNet'] +
                    $data['workingPriceNetYearly']) / (int)$data['downPaymentInterval'];

            $data['monthlyPayments'] = [];
            for ($i = 1; $i <= (int)$data['downPaymentInterval']; $i++) {
                $mPayment = $data['monthlyDownPayment'];
                foreach ($data['bonus'] as $bonus) {
                    if ($i > $bonus['paymentAfterMonths']) {
                        // add here the bonus on the staring monthly down payment, not the resulted
                        $mPayment -= ($data['monthlyDownPayment'] * ((float)$bonus['value'] / 100));
                    }
                }
                $data['monthlyPayments'][$i] = round($mPayment + ($mPayment * ($data['vat'] / 100)), 2);
            }

            return $data;
        }

    }