<?php
require_once(__DIR__.'/htattrs.php');
require_once(__DIR__.'/htinput.php');
require_once(__DIR__.'/htcombo.php');
require_once(__DIR__."/htag.php");

function htform_parse_fields(array $fields){
    $parsed = [];
    foreach($fields as $field => $attrs){
        if(strstr($field,':')){
            [$name, $func] = explode(':', $field);
        } else {
            [$name, $func] = [$field, 'htinput'];
        }
        $attrs['name'] = $name;
        $parsed[$name] = ['attrs' => $attrs, 'func' => $func];
    }
    return $parsed;
}

function htform(array $attrs){
    $options = [];
    foreach(['data','button','fields','button_attrs'] as $k){
        $options[$k] = $attrs[$k]??null;
        unset($attrs[$k]);
    }
    $elements = [];
    foreach(htform_parse_fields($options['fields']??[]) as $field){
        $fattrs = $field['attrs'];
        $name = $fattrs['name'];
        if(isset($options['data'][$name])){
            $fattrs['value'] = $options['data'][$name];
        }
        $content = $field['func']($fattrs);
        $elements[] = htag('div.form-field', ['data-field'=>$name], $content);
    }
    if(!empty($options['button'])){
        $elements[] = htag('button', $options['button_attrs']??[], $options['button']);
    }
    return htag('form', $attrs, $elements);
}

