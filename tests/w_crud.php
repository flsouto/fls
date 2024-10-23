<?php

$store = '/tmp/w_crud_test.json';

if(!is_demo()){
    @unlink($store);
}

$html = '';
$render = function(array $options=[]){
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
                    3 => 'Guest'
                ]
            ]
        ],
        ...$options
    ]);

};

// TEST READ
$render();
assert_contains_in_order($html, ['class="w_crud','Add','<table']);

// TEST CREATE
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

for($i=1;$i<=3;$i++){
    $insert_id = null;
    $_POST['email'] = 'test'.$i.'@domain.com';
    $_POST['user_type'] = "$i";
    $render([
        'success' => function($id){
            $GLOBALS['insert_id'] = $id;
        }
    ]);
    assert_empty(w_error_msg_get());
    assert_not_empty($insert_id);
}

apply_redirect();

$render();

assert_contains_in_order($html, [
    '<tr',
    'test1@domain.com</td>',
    'Admin</td>',
    '<tr',
    'test2@domain.com</td>',
    'User</td>',
    '<tr',
    'test3@domain.com</td>',
    'Guest</td>',
]);


// TEST UPDATE
$_GET['edit'] = 1;
$_GET['id'] = $insert_id;
$_SERVER['REQUEST_METHOD'] = 'GET';

$render();
assert_contains($html, $_POST['email']);

// TEST DELETE



