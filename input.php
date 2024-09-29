<?php

function input($prompt=""){
    $stdin = fopen("php://stdin","r");
    if($prompt) echo $prompt."\n";
    return trim(fgets($stdin));
}
