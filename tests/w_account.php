<?php

if(!is_demo()){
    exec("rm w_account_test_*.json 2>/dev/null");
}

// Test GET login
$html = w_account($params=[
    'store' => 'w_account_test_%s.json',
    'home_url' => '/success_url'
]);

expose($html, 'default');

assert_contains($html, '<form');
assert_contains($html, '<button');
assert_contains($html, 'href="?signup');

// Test GET signup
$_GET['signup'] = 1;
$html = w_account($params);
expose($html, 'signup');

assert_not_contains($html,'href="?signup');
assert_contains($html, 'action="?signup');

// Test POST signup without email
$_SERVER['REQUEST_METHOD'] = 'POST';
$html = w_account($params);

assert_not_empty($error_msg);
assert_contains($error_msg, "mail");
assert_contains($html, $error_msg);

// Test POST signup without password
$_POST['email'] = 'user@domain.com';
$html = w_account($params);
assert_not_empty($error_msg);
assert_contains($error_msg, 'password');

// Test POST signup succeeds
$_POST['password'] = '12345';
$html = w_account($params);
assert_empty($error_msg);
assert_not_empty($redirect_location);
expect($redirect_location, $params['home_url']);
assert_not_empty($_SESSION['uid']);

// Test signup with same data fails
unset($_SESSION['uid']);
$html = w_account($params);
assert_not_empty($error_msg);
assert_contains($error_msg, 'exists');

// Test POST signin with same data succeeds
unset($_GET['signup']);
$html = w_account($params);
assert_empty($error_msg);
assert_not_empty($_SESSION['uid']);
expect($redirect_location, $params['home_url']);

// Test POST signin fails
unset($_SESSION['uid']);
$_POST = [];
$html = w_account($params);
assert_not_empty($error_msg);
assert_contains($html, $error_msg);
