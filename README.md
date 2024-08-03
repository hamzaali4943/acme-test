# Acme Widget Co Sales System

This is a proof of concept implementation of the Acme Widget Co sales system. It provides a `Basket` class that allows adding products and calculating the total cost, taking into account delivery charges and special offers.

## How it works

1. The `Basket` class is initialized with a product catalog, delivery charge rules, and special offers.
2. Products can be added to the basket using the `add` method, which takes a product code as a parameter.
3. The `total` method calculates the total cost of the basket, applying delivery charges and special offers.

## Assumptions

1. The product catalog is provided as an associative array with product codes as keys and prices as values.
2. Delivery charge rules are provided as an array of associative arrays, each containing a 'threshold' and a 'charge'.
3. Special offers are provided as an associative array with product codes as keys and offer details as values.
4. The "buy one red widget, get the second half price" offer is applied to pairs of red widgets, rounding down for odd numbers of red widgets.
5. If a product code is not found in the catalog, an exception is thrown.
6. The delivery charge rules are applied in descending order of threshold values.

## Usage

```php
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
$basket->add('R01');
$basket->add('G01');
echo $basket->total(); // Outputs the total cost