<?php

$html = w_success_msg('Data saved successfully');

assert_contains($html, 'successfully');
assert_contains($html, 'success_msg');

expose($html);

$w_success_msg = "From globals";

$html = w_success_msg();
assert_contains($html, 'From globals');

$w_success_msg = null;
$_SESSION['w_success_msg'] = 'From session';
$html = w_success_msg();
assert_contains($html, 'From session');

w_success_msg_set("from setter");
assert_contains($w_success_msg, "from setter");
