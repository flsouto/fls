<?php

function take($arr, ...$args){
    $result = [];
    foreach($args as $k){
        $result[] = $arr[$k]??null;
        unset($arr[$k]);
    }
    array_unshift($result, $arr);
    return $result;
}

