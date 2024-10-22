<?php

$html = htable([
    'data' => [
        ['id' => 1, 'name' => 'PHP'],
        ['id' => 2, 'name' => 'JS'],
        ['id' => 3, 'name' => 'HTML']
    ]
]);

expose($html);

assert_contains_in_order($html, [
    '<table',
    '<tbody',
    '<tr','1</td>','PHP</td>',
    '<tr','2</td>','JS</td>',
    '<tr','3</td>','HTML</td>'
]);
