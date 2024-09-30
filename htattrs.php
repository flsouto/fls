<?php

function htattrs(array $attrs=[]){
    foreach($attrs as $k => $v){
        $strs[] = $k.'="'.$v.'"';
    }
    return implode(" ",$strs);
}

