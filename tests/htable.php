<?php

$html = htable([
    'border' => 1,
    'class' => 'languages',
    'data' => [
        ['id' => 1, 'name' => 'PHP'],
        ['id' => 2, 'name' => 'JS'],
        ['id' => 3, 'name' => 'HTML']
    ],
    'actions' => [
        'Edit' => [
            'href' => ['id'=>'{id}']
        ],
        'Remove' => [
            'href' => '?rm=1&id={id}',
            'confirm' => 'Are you sure?',
            'style'=>['color'=>'red']
        ]
    ]
]);

expose($html);

assert_contains_in_order($html, [
    '<table',
    'class="htable languages"',
    '<thead',
    '<th','Id',
    '<th','Name',
    '<tbody',
    '<tr','1</td>','PHP</td>', '<td', '?id=1','Edit', 'Are you sure','rm=1&amp;id=1', 'Remove',
    '<tr','2</td>','JS</td>', '<td', '?id=2','Edit', 'Are you sure','rm=1&amp;id=2', 'Remove',
    '<tr','3</td>','HTML</td>', '<td', '?id=3','Edit', 'Are you sure','rm=1&amp;id=3', 'Remove'
]);
