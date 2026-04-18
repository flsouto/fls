<?php

require_once(__DIR__."/boot.php");

if($argv[1]??''){
    $state['tid'] = $argv[1];
    $state->save();
}

foreach($state['tabs']??[] as $tab){
    if($tab['id'] == $state['tid']) echo "----> ";
    echo "#".$tab['id']." ".$tab['name']." ($tab[count])\n";
}

