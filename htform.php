<?php
require_once(__DIR__.'/htattrs.php');
require_once(__DIR__.'/htinput.php');
require_once(__DIR__.'/htcombo.php');
require_once(__DIR__."/htag.php");

function htform(array $attrs){
    $options = [];
    foreach(['data','button','fields','button_attrs'] as $k){
        $options[$k] = $attrs[$k]??null;
        unset($attrs[$k]);
    }
    $elements = [];
    foreach($options['fields']??[] as $field => $field_attrs){
        if(strstr($field,':')){
            [$name, $func] = explode(':', $field);
        } else {
            [$name, $func] = [$field, 'htinput'];
        }
        if(isset($options['data'][$name])){
            $field_attrs['value'] = $options['data'][$name];
        }
        $elements[] = htag('div', ['class'=>'form-field', 'data-field'=>$name], $func($field_attrs));
    }
    if(!empty($options['button'])){
        $elements[] = htag('button', $options['button_attrs']??[], $options['button']);
    }
    return htag('form', $attrs, $elements);
}

