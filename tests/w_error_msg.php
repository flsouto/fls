<?php


$html = w_error_msg('Invalid credentials');

assert_contains($html, 'Invalid');
assert_contains($html, 'error_msg');

expose($html);

$w_error_msg = "From globals";

$html = w_error_msg();
assert_contains($html, 'From globals');

$w_error_msg = null;
$_SESSION['w_error_msg'] = 'From session';
$html = w_error_msg();
assert_contains($html, 'From session');

w_error_msg_set("from setter");
assert_contains($w_error_msg, "from setter");
