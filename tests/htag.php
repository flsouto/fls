<?php

$html = htag('a', ['href' => 'http://domain.com/'], "link");

assert_contains_in_order($html, [
    "<a",
    'href="http',
    '>link',
    '</a>'
]);

$html = htag('ul', [], [
    htag('li', [], 'Item 1'),
    htag('li', [], 'Item 2'),
    htag('li', [], 'Item 3')
]);

assert_contains_in_order($html, [
    '<ul',
    '<li',
    'Item 1',
    '</li>',
    'Item 2',
    'Item 3'
]);
