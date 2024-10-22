<?php

function htattrs(array $attrs=[]){
    $strs = [];
    foreach($attrs as $k => $v){
        if(is_bool($v)){
            if($v) $strs[] = "$k";
        } else if($k == 'class' && is_array($v)) {
            $strs[] = $k.'="'.implode(' ',$v).'"';
        } else if($k == 'style' && is_array($v)) {
            $strs[] = $k.'="'.htattrs_style($v).'"';
        } else {
            $strs[] = $k.'="'.htmlentities($v).'"';
        }
    }
    return implode(" ",$strs);
}

function htattrs_style($array){
    $strs = [];
    foreach($array as $k=>$v){
        $strs[] = "$k:$v;";
    }
    return implode($strs);
}

function htattrs_add_class(array $attrs, $class, $prepend=false){
    if(isset($attrs['class'])){
        if($prepend){
            $attrs['class'] = $class.' '.$attrs['class'];
        } else {
            $attrs['class'] .= ' '.$class;
        }
    } else {
        $attrs['class'] = $class;
    }
    return $attrs;
}
