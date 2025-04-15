<?php

return [
    'roles' => [
        'admin' => 'admin',
        'customer' => 'customer',
    ],

    'order_status' => [
        'pending' => 'pending',
        'processing' => 'processing',
        'completed' => 'completed',
        'canceled' => 'canceled',
    ],
    'payment_methods' => [
        'credit_card' => 'credit_card',
        'paypal' => 'paypal',
        'bank_transfer' => 'bank_transfer',
    ],
    'shipping_methods' => [
        'standard' => 'standard',
        'express' => 'express',
        'overnight' => 'overnight',
    ],
];
