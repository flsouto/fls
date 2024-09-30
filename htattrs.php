<?php

function htattrs(array $attrs=[]){
    $strs = [];
    foreach($attrs as $k => $v){
        if(is_bool($v)){
            if($v) $strs[] = "$k";
        } else {
            $strs[] = $k.'="'.$v.'"';
        }
    }
    return implode(" ",$strs);
}

