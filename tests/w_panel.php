<?php


$html = w_panel($params=[
    'loader' => fn($script) => w_panel_load(__DIR__."/w_panel.$script"),
    'menu' => [
        'index' => 'Home Page',
        'page1' => 'This is Page 1',
        'page2:page2.html' => 'This is Page 2'
    ]
]);

expose($html);

assert_contains($html, 'Home Page</h1>');
assert_contains($html, 'Hello from index!');

$_GET['p'] = 'page1';
$html = w_panel($params);
assert_contains($html, 'This is Page 1</h1>');
assert_contains($html, 'Hello from page 1');

$_GET['p'] = 'page2';
$html = w_panel($params);
assert_contains($html, 'This is Page 2</h1>');
assert_contains($html, 'Hello from page 2');
