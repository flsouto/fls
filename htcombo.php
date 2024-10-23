<?php

require_once(__DIR__.'/htattrs.php');
require_once(__DIR__.'/htag.php');

function htcombo_parse_options(array $options){
    $parsed = [];
    foreach($options as $id => $label){
        if(is_array($label) && isset($label['id'])){
            $id = $label['id'];
        }
        if(is_array($label) && isset($label['name'])){
            $label = $label['name'];
        }
        $parsed[$id] = $label;
    }
    return $parsed;
}

function htcombo($attrs = []){
    $value = $attrs['value']??null;
    $options = $attrs['options']??[];
    $caption = $attrs['caption']??null;
    unset($attrs['value'],$attrs['options'],$attrs['required']);
    $htopts = [];
    if($caption){
        $htopts[] = htag('option',['value'=>''],$caption);
    }
    foreach(htcombo_parse_options($options) as $id => $label){
        $selected = $id == $value ? ' selected' : '';
        $htopts[] = htag('option',['value'=>$id, 'selected'=>$id==$value], $label);
    }
    return htag('select', $attrs, $htopts);
};

function htcombo_handle(array $attrs, array $data){

    require_once(__DIR__."/htinput.php");
    $result = htinput_handle($attrs, $data);
    if(!empty($result['error'])){
        return $result;
    }
    if(empty($result['value'])){
        return $result;
    }
    $value = $result['value'];
    $options = htcombo_parse_options($attrs['options']??[]);
    if(!isset($options[$value])){
        return ['error' => "Invalid option: $value"];
    }
    return [
        'value' => $value
    ];
}
