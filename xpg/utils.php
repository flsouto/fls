<?php

function jsonstr($data){

    $json = json_encode($data,JSON_PRETTY_PRINT);

    $json = preg_replace_callback('/^( +)/m', function ($matches) {
        return str_repeat(' ', strlen($matches[1]) / 2);
    }, $json);

    return $json;
}

function decval($table, $column, $value){
    global $schema;
    if(str_starts_with($schema['types'][$table][$column]??'','json')){
        $value = json_decode($value,true);
    }
    return $value;
}

function decrow($table, $row){
    foreach($row as $k => &$v){
        $v = dval($table, $k, $v);
    }
    return $row;
}

function decrows($table, $rows){
    return array_map(fn($row) => decrow($table, $row), $rows);
}


