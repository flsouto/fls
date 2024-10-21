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

$html = htag('ul', [
    'class' => 'list',
    htag('li', [], 'Item 1'),
    htag('li', [], 'Item 2'),
    htag('li', [], 'Item 3')
]);


assert_contains_in_order($html, [
    '<ul',
    'class="list"',
    '<li',
    'Item 1',
    '</li>',
    'Item 2',
    'Item 3'
]);

$html = htag('div.box#123',[
    htag('span.the-title', "Blah")
]);

assert_contains_in_order($html,[
    "<div",
    'class="box"',
    'id'=>'123',
    '<span',
    'class="the-title"',
    'Blah</span>',
    '</div>'
]);

$html = htag('a.rm',[
    'Remove',
    'hotkey' => 'r',
]);

assert_contains_in_order($html, [
    '<a',
    'id=',
    'Remove',
    '<script',
    'addEventListener',
    'if',
    'click'
]);

