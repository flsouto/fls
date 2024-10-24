<?php

$store = '/tmp/w_crud_form_test.json';

if(!is_demo()){
    @unlink($store);
}

$html = null;

$render = function(array $options=[]){
    global $html;
    global $redirect_location;
    global $store;

    $redirect_location = null;

    $html = w_crud_form([
        'store' => $store,
        'fields' => [
            'email:htinput' => [
                'placeholder' => 'E-mail',
                'required' => true,
                'regex' => '/\w+@\w+\.\w+/'
            ],
            'user_type:htcombo' => [
                'required' => true,
                'options' => [
                    1 => 'Admin',
                    2 => 'User',
                    2 => 'Guest'
                ]
            ]
        ],
        ...$options
    ]);
};

// TEST CREATE

$render();

assert_contains_in_order($html,[
    '<form',
    'method="POST"',
    '<input',
    'name="email"',
    '<select',
    'Admin',
    'Save'
]);

expose($html);

$_SERVER['REQUEST_METHOD'] = 'POST';

$render();

assert_contains($html, 'error_msg');
assert_contains(w_error_msg_get(), 'email');

$_POST['email'] = 'dsfdsf';
$render();

assert_contains(w_error_msg_get(), ['email','invalid']);

$_POST['email'] = 'user@domain.com';
$render();

assert_contains(w_error_msg_get(), 'user_type');
assert_contains($html, $_POST['email']);

$_POST['user_type'] = 1;
$render();
assert_empty(w_error_msg_get());
assert_not_empty(w_success_msg_get());
assert_not_empty(redirect_location());

$insert_id = null;

$render([
    'success' => function($id){
        global $insert_id;
        $insert_id = $id;
    }
]);

assert_not_empty($insert_id);

$db = jsondb($store);
assert_not_empty($db[$insert_id]);
assert_not_empty($db[$insert_id]['email']);
expect($db[$insert_id]['id'], $insert_id);

expect($db[$insert_id]['email'], $_POST['email']);
expect($db[$insert_id]['user_type'], $_POST['user_type']);

// TEST UPDATE
$_SERVER['REQUEST_METHOD'] = 'GET';
$_POST = [];

$_GET['id'] = $insert_id;

$render();

assert_contains($html, 'value="'.$db[$insert_id]['email']);
assert_contains($html, 'value="'.$db[$insert_id]['user_type']);

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['email'] = 'changed@domain.com';
$_POST['user_type'] = '';

$render();
assert_not_empty(w_error_msg_get());

$_POST['user_type'] = '2';
$updated_id = null;

$render([
    'success' => function($id){
        global $updated_id;
        $updated_id = $id;
    }
]);

assert_empty(w_error_msg_get());
assert_not_empty($updated_id);
expect($insert_id, $updated_id);

$db = jsondb($store);
expect($db[$insert_id]['email'], $_POST['email']);
expect($db[$insert_id]['user_type'], $_POST['user_type']);
