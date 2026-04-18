<?php

require_once(__DIR__."/boot.php");

$ids = array_column($tab['data'],'id');

$result = qkv('SELECT id, "'.$argv[1].'" FROM "'.$tab['table'].'" WHERE id IN(?)', [$ids]);

foreach($tab['data'] as &$row){
    $value = &$result[$row['id']];
    $value = decval($tab['table'], $argv[1], $value);
    $row[$argv[1]] = $value;
}

$tab->save();

passthru('php view.php');
