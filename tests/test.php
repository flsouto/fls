<?php

$info = null;

function info($v){
    global $info;
    $info = $v;
}

function _error($msg){
    global $info;
    if(is_demo()) return;
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

function assert_file_not_exists($file){
    if(file_exists($file)){
        _error("File exists: $file");
    }
}

function assert_contains($content, $substr){
    if(!stristr($content, $substr)){
        _error("Failed checking that '$content' contains '$substr'");
    }
}

function assert_contains_in_order($string, $values) {
    $currentPos = 0;

    foreach ($values as $value) {
        $currentPos = strpos($string, $value, $currentPos);
        if ($currentPos === false) {
            return _error("Failed checking that '$string' has '$value' in the expected order.");
        }

        $currentPos += strlen($value);
    }
}

function assert_contains_all($content, $substr){
    if(!stristr($content, $substr)){
        _error("Failed checking that '$content' contains '$substr'");
    }
}

function assert_not_contains($content, $substr){
    if(stristr($content, $substr)){
        _error("Failed checking that '$content' does not contain '$substr'");
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

function assert_empty($val){
    if(!empty($val)){
        _error("Value should be empty but contains: $val");
    }
}

function apply_redirect(){
    $q = explode('?',redirect_location())[1] ?? '';
    parse_str($q, $_GET);
}

function click_link($html,$text){
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $nodes = $xpath->query("//a[contains(text(), '$text')]");

    if (!$nodes->length ) {
        _error("Link not found: $text");
    }
    $element = $nodes->item(0);
    if(!$href = $element->attributes['href'] ?? null){
        _error("Link '$text' has empty href.");
    }
    $q = explode('?',$href->textContent)[1] ?? '';
    parse_str($q, $_GET);
}

function expose($content, $id='index'){
    if($id == ($GLOBALS['expose']??'')){
        echo $content;
        die();
    }
}

function is_demo(){
    return !empty($_SERVER['REQUEST_URI']);
}

$file = $argv[1]??null;

assert_not_empty($file);

assert_file_exists($f = __DIR__."/../$file");
require $f;

assert_file_exists($f = __DIR__."/$file");
require $f;
