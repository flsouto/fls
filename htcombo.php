<?php

require_once(__DIR__.'/htattrs.php');
require_once(__DIR__.'/htag.php');

function htcombo($attrs = []){
    $value = $attrs['value']??null;
    $options = $attrs['options']??[];
    unset($attrs['value'],$attrs['options']);
    $htopts = [];
    foreach($options as $label => $id){
        $selected = $id == $value ? ' selected' : '';
        $htopts[] = htag('option',['value'=>$id, 'selected'=>$id==$value], $label);
    }
    return htag('select', $attrs, $htopts);
};
