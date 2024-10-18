<?php

$html = w_dialog([
    'class' => 'my_dialog',
    'title' => 'Test Dialog',
    'content' => 'Lorem Ipsum',
    'actions' => [
        'Next' => [
            'href' => '?next=1'
        ],
        'Prev' => [
            'href' => '?prev=1'
        ]
    ],
    'style' => '/** custom style */'
]);

assert_contains_in_order($html, [
    '<div',
    'class="w_dialog my_dialog"',
    'Test Dialog',
    'Lorem Ipsum',
    '<a',
    '?next=1',
    'Next',
    '?prev=1',
    'Prev',
    '<style',
    '/** custom style */'
]);
