<?php

$html = htinput($options=[
    'name' => 'file',
    'value' => 'index.php',
    'regex' => '/\w+.php/i',
    'required' => true
]);

assert_contains($html, '<input');
assert_contains($html, 'name="file"');

$result = htinput_handle($options, [
    'file' => 'test.php',
    'required' => true
]);

assert_not_empty($result['value'],'test.php');

$result = htinput_handle($options, [
    'file' => '||||.php'
]);

assert_not_empty($result['error']);

$result = htinput_handle($options, [
    'file' => ''
]);

assert_not_empty($result['error']);

$result = htinput_handle([...$options,'required'=>false], [
    'file' => ''
]);

assert_empty($result['error']??'');





