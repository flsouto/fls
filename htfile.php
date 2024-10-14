<?php
require_once(__DIR__."/htag.php");

function htfile(array $options=[]){
    $attrs = $options; // todo extract stuff
    return htag('input',[
        'type' => 'file',
        ...$attrs
    ]);
}
