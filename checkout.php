<?php

use Stripe\Stripe;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

$name = $_POST['name'];
$price = $_POST['price'];

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'success_url' => $_ENV['DOMAIN'] . '/success.php',
        'cancel_url' => $_ENV['DOMAIN'] . '/cancel.php',
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'LKR',
                'unit_amount' => $price,
                'product_data' => [
                    'name' => $name,
                ],
            ],
            'quantity' => 1,
        ]],
    ]);
    http_response_code(303);
    header("Location: " . $checkout_session->url);
} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}

?>