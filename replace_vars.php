<?php

function replace_vars($template, array $vars){
    return preg_replace_callback("/\{(\w+)\}/",function($m) use($vars){
        return $vars[$m[1]]??null;
    },$template);
}
