<?php

$form = htform([
    'data' => $_REQUEST,
    'method' => 'POST',
    'button' => 'Register',
    'data' => [
        'name' => 'Somebody',
        'email' => 'test@domain.com',
        'type' => 'staff'
    ],
    'fields' => [
        'name' => [
            'placeholder' => 'Your Name'
        ],
        'email:htinput' => [
            'type' => 'email',
            'placeholder' => 'Your Email'
        ],
        'type:htcombo' => [
            'options' => ['Admin' => 'admin', 'Staff'=>'staff']
        ]
    ],
    'button' => 'Action',
    'button_attrs' => ['onclick'=>'jsfunc()']
]);

assert_contains($form, 'Your Name');
assert_contains($form, 'type="email"');
assert_contains($form, 'value="test@domain.com"');
assert_contains($form, 'value="staff" selected');
assert_contains($form, 'Action');
assert_contains($form, 'onclick');
assert_contains($form, 'method="POST"');
