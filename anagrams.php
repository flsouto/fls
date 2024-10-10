<?php

function anagrams($word,$iters=999){
    $arr = [];

    for($i=1;$i<=999;$i++){
        $arr[str_shuffle("$word")] = 1;
    }

    return array_keys($arr);

}

