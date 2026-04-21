<?php

require_once(__DIR__."/boot.php");


$row = $tab['data'][$argv[1]] ?? null;


if(!$row){
    die("No row found for index $argv[1]\n");
}

update($tab['table'], [$argv[2] => $argv[3]], ['id' => $row['id']]);

$tab['data'][$argv[1]][$argv[2]] = $argv[3];

$tab->save();

