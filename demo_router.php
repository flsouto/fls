<?php

$parts = explode('/',$_SERVER['REQUEST_URI']);

$argv[1] = $parts[1].".php";
$expose = $parts[2]??1;
if(strstr($expose,'?')){
    [$expose,$query] = explode('?',$expose);
}

require(__DIR__."/tests/test.php");
