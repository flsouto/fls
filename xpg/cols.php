<?php

require_once(__DIR__.'/boot.php');

$table = $argv[1] ? findtbl($argv[1]) : $tab['table'];

foreach($schema['types'][$table] as $k => $v){
    echo "- $k: $v\n";
}

