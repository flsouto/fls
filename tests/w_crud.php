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

expose($html);

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
    '?edit=1&amp;id='.$insert_id,
]);



// TEST UPDATE
$_GET['edit'] = 1;
$_GET['id'] = $insert_id;

$render();
assert_contains($html, $_POST['email']);

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['email'] = 'changed@domain.com';
$render();

assert_not_empty(redirect_location());

apply_redirect();

$render();

assert_contains_in_order($html, ['<table','test2@domain.com','changed@domain.com']);

// TEST DELETE
$_GET['rm'] = 1;
$_GET['id'] = $insert_id;
$render([
    'deleted' => function($id,$row){
        $GLOBALS['deleted_id'] = $id;
        $GLOBALS['deleted_email'] = $row['email'];
    }
]);
assert_not_empty($deleted_id);
expect('changed@domain.com',$deleted_email);
apply_redirect();

$render();

assert_not_contains($html, 'changed@domain.com');



