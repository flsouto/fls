<?php
$form = htform([
    'method' => 'POST',
    'button' => 'Register',
    'data' => array_merge([
        'name' => 'Somebody',
        'email' => 'test@domain.com',
        'type' => 'staff'
    ],$_POST),
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
    'button_attrs' => ['onclick'=>"alert('OK')"]
]);

expose($form);

assert_contains($form, 'name="email"');
assert_contains($form, 'Your Name');
assert_contains($form, 'type="email"');
assert_contains($form, 'value="test@domain.com"');
assert_contains($form, 'value="staff" selected');
assert_contains($form, 'Action');
assert_contains($form, 'onclick');
assert_contains($form, 'method="POST"');

$form = htform([
    'method' => 'GET',
    'data' => $_GET,
    'fields' => [
        'rating' => [
            'placeholder' => 'Rating'
        ]
    ],
]);

expose($form, 'rating');

assert_contains($form, 'method="GET"');
assert_contains($form, 'placeholder="Rating"');

