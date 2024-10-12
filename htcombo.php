<?php

require_once(__DIR__.'/htattrs.php');
require_once(__DIR__.'/htag.php');

function htcombo($attrs = []){
    $value = $attrs['value']??null;
    $options = $attrs['options']??[];
    unset($attrs['value'],$attrs['options']);
    $htopts = [];
    foreach($options as $id => $label){
        $selected = $id == $value ? ' selected' : '';
        if(is_array($label) && isset($label['name'])){
            $label = $label['name'];
        }
        $htopts[] = htag('option',['value'=>$id, 'selected'=>$id==$value], $label);
    }
    return htag('select', $attrs, $htopts);
};
