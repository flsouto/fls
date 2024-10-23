<?php

function replace_vars($template, array $vars){
    if(is_array($template)){
        $result = [];
        foreach($template as $k => $v){
            $result[$k] = replace_vars($v, $vars);
        }
        return $result;
    }
    return preg_replace_callback("/\{(\w+)\}/",function($m) use($vars){
        return $vars[$m[1]]??null;
    },$template);
}
