<?php

class Basket {
    private $catalog;
    private $deliveryRules;
    private $offers;
    private $items = [];

    public function __construct($catalog, $deliveryRules, $offers) {
        $this->catalog = $catalog;
        $this->deliveryRules = $deliveryRules;
        $this->offers = $offers;
    }

    public function add($productCode) {
        if (isset($this->catalog[$productCode])) {
            $this->items[] = $productCode;
        } else {
            throw new Exception("Product code not found in catalog.");
        }
    }

    public function total() {
        $subtotal = 0;
        $redWidgetCount = 0;

        foreach ($this->items as $item) {
            $subtotal += $this->catalog[$item];
            if ($item == 'R01') {
                $redWidgetCount++;
            }
        }

        // Apply "buy one red widget, get the second half price" offer
        if ($redWidgetCount >= 2) {
            $discount = floor($redWidgetCount / 2) * ($this->catalog['R01'] / 2);
            $subtotal -= $discount;
        }

        // Apply delivery charge
        $deliveryCharge = $this->calculateDeliveryCharge($subtotal);

        return $subtotal + $deliveryCharge;
    }

    private function calculateDeliveryCharge($subtotal) {
        foreach ($this->deliveryRules as $rule) {
            if ($subtotal >= $rule['threshold']) {
                return $rule['charge'];
            }
        }
        return end($this->deliveryRules)['charge']; // Default to the highest charge
    }
}

// Example usage:
$catalog = [
    'R01' => 32.95,
    'G01' => 24.95,
    'B01' => 7.95
];

$deliveryRules = [
    ['threshold' => 90, 'charge' => 0],
    ['threshold' => 50, 'charge' => 2.95],
    ['threshold' => 0, 'charge' => 4.95]
];

$offers = [
    'R01' => ['type' => 'half_price_second', 'quantity' => 2]
];

$basket = new Basket($catalog, $deliveryRules, $offers);

// Test case as per your defined in pdf
$testCases = [
    ['B01', 'G01'],
    ['R01', 'R01'],
    ['R01', 'G01'],
    ['B01', 'B01', 'R01', 'R01', 'R01']
];

foreach ($testCases as $case) {
    $basket = new Basket($catalog, $deliveryRules, $offers);
    foreach ($case as $item) {
        $basket->add($item);
    }
    echo "Items: " . implode(', ', $case) . " - Total: $" . number_format($basket->total(), 2) . "\n";
}