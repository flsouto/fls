<?php

$store = '/tmp/w_crud_test.json';

$html = '';
$render = function(){
    global $html, $store, $redirect_location;

    $redirect_location = null;

    $html = w_crud([
        'store' => $store,
        'fields' => [
            'email:htinput' => [
                'placeholder' => 'E-mail',
                'required' => true,
                'regex' => '/\w+@\w+\.\w+/'
            ],
            'user_type:htcombo' => [
                'required' => true,
                'caption' => 'Choose type:',
                'options' => [
                    1 => 'Admin',
                    2 => 'User',
                    2 => 'Guest'
                ]
            ]
        ],
    ]);
};

$render();

assert_contains_in_order($html, ['class="w_crud','Add','<table']);

click_link($html, 'Add');
$render();

assert_contains($html, [
    'class="w_crud"',
    '<form','method="POST',
    '<input',
    'name="email"',
    '<select',
    'name="user_type"',
    'Choose type:',
    'Admin</option>'
]);

$_SERVER['REQUEST_METHOD'] = 'POST';
$render();

assert_not_empty(w_error_msg_get());

$_POST['email'] = 'test@domain.com';
$_POST['user_type'] = '1';

$render();

assert_empty(w_error_msg_get());

apply_redirect();

$render();

assert_contains($html, 'Admin</td>');
assert_contains($html, 'test@domain.com</td>');




