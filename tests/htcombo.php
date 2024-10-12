<?php

$out = htcombo([
    'options' => [
        1 => 'One',
        2 => 'Two'
    ]
]);

assert_not_contains($out, "selected");

expose($out, 1);

$out = htcombo([
    'options' => [
        1 => 'One',
        2 => 'Two',
        3 => ['name' => 'Three','xxx'=>'XX']
    ],
    'value' => 2,
    'id' => 'test'
]);

assert_contains($out,'option value="2" selected');
assert_contains($out,'select id="test"');
assert_contains($out,'value="3">Three<');

expose($out, 2);
