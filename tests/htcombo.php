<?php

$out = htcombo([
    'options' => [
        'One' => 1,
        'Two' => 2
    ],
    'value' => 2,
    'id' => 'test'
]);

assert_contains($out,'option value="2" selected');
assert_contains($out,'select id="test"');

$out = htcombo([
    'options' => [
        'One' => 1,
        'Two' => 2
    ]
]);

assert_not_contains($out, "selected");
