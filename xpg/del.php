<?php

require_once(__DIR__."/boot.php");

foreach($tab['data'] as &$row){
    unset($row[$argv[1]]);
}

$tab->save();

view();
