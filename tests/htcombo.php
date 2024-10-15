<?php

$out = htcombo([
    'caption' => 'Select a number:',
    'options' => [
        1 => 'One',
        2 => 'Two'
    ]
]);

assert_not_contains($out, "selected");
assert_contains($out, "Select a number");

expose($out);

$out = htcombo([
    'options' => [
        1 => 'One',
        2 => 'Two',
        ['id'=>3, 'name' => 'Three','created'=>time()]
    ],
    'value' => 2,
    'id' => 'test',
]);

assert_contains($out,'option value="2" selected');
assert_contains($out,'select id="test"');
assert_contains($out,'value="3">Three<');

expose($out, 2);

$result = htcombo_handle([
    'name' => 'test',
    'required' => true
],[]);

assert_not_empty($result['error']);

$result = htcombo_handle([
    'name' => 'test',
    'required' => true,
    'options' => [
        'OK' => 'Oukay'
    ]
],['test'=>'OK']);

assert_empty($result['error']??'');

$result = htcombo_handle([
    'name' => 'test',
    'options' => [
        1 => 'One'
    ]
],['test'=>2]);

assert_not_empty($result['error']);

$result = htcombo_handle([
    'name' => 'test',
    'required' => true,
    'options' => [
        'OK' => 'Oukay'
    ]
],['test'=>'NOTOK']);

assert_not_empty($result['error']);
