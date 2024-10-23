<?php

require_once(__DIR__.'/htattrs.php');

function htinput(array $attrs){
    foreach(['regex'] as $field){
        unset($attrs[$field]);
    }
    $attrs = htattrs($attrs);
    return "<input $attrs />";
}

function htinput_handle(array $attrs, array $data){
    $name = $attrs['name'];
    $value = $data[$name]??'';
    if(!empty($attrs['required']) && !$value){
        return ['error' => "$name is required!"];
    }
    if(empty($attrs['required']) && !$value){
        return ['value' => ''];
    }
    if(!empty($attrs['regex'])){
        if(!preg_match($attrs['regex'], $data[$name])){
            return ['error' => "The value for '$name' is invalid: $value"];
        }
    }
    return ['value' => $value];
}
