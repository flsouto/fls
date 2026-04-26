<?php

require_once(__DIR__."/boot.php");

$table = findtbl($argv[1]);

if(empty($argv[2])){
    $fks = [];
    foreach($schema['relations'] as $rel){
        if($table == $rel['child'] && $rel['parent']==$tab['table']){
            $fks[] = $rel['fk'];
        }
    }
    if(count($fks) > 1){
        die("Multiple fks found for child table: ".implode(', ',$fks)."\n");
    }
    $fk = $fks[0];
} else {
    $fk = $argv[2];
}

$ids = array_column($tab['data'],'id');
$query = sprintf('SELECT id FROM "%s" WHERE "%s" IN(?)',$table,$fk);
$rows = query($query, [$ids]);
if(empty($rows)){
    die("No children found in $argv[1]\n");
}

newtab($table,$rows,$argv[1].' of '.$tab['name']);
view();
