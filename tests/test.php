<?php

$info = null;

function info($v){
    global $info;
    $info = $v;
}

function _error($msg){
    global $info;
    $dbt = debug_backtrace();
    $dbt = $dbt[1];
    echo $msg."\n";
    echo "At $dbt[file]:$dbt[line]\n";
    if($info){
        echo "Info: $info\n";
    }
    exit(1);
}

function assert_file_exists($file){
    if(!file_exists($file)){
        _error("File not found: $file");
    }
}

function expect($a, $b){
    if($a != $b){
        _error("Expected $a to be $b");
    }
}

function assert_true($val){
    if(!$val){
        _error("Value should be true");
    }
}

function assert_not_empty($val){
    if(empty($val)){
        _error("Value should not be empty");
    }
}

$file = $argv[1]??null;

assert_not_empty($file);

assert_file_exists($f = __DIR__."/../$file");
require $f;

assert_file_exists($f = __DIR__."/$file");
require $f;
