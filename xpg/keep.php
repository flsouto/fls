<?php

require_once(__DIR__."/boot.php");

foreach($tab['data'] as $i=>$row){
    $str = json_encode($row);
    if(!stristr($str, $argv[1])){
        unset($tab['data'][$i]);
    }
}
$tab['data'] = array_values($tab['data']);
$tab->save();

view();
