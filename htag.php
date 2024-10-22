<?php
require_once(__DIR__."/htattrs.php");
require_once(__DIR__."/htquery.php");
require_once(__DIR__."/htjs.php");

function htag($type, $attrs=[], $content=null){
    if(!is_array($attrs)){
        $attrs = [$attrs];
    }
    preg_match("/([\w-]+)([.#][\w-]+)?([.#][\w-]+)?/",$type,$matches);
    $type = $matches[1];
    for($i=2;$i<=3;$i++){
        if(!isset($matches[$i])) continue;
        $chr = substr($matches[$i],0,1);
        $attr = ['.'=>'class','#'=>'id'][$chr];
        $attrs[$attr] = substr($matches[$i],1);
    }
    if(!$content){
        $content = [];
        foreach($attrs as $k => $v){
            if(is_int($k)){
                $content[] = $v;
                unset($attrs[$k]);
            }
        }
    }
    if(is_array($content)){
        $content = implode($content);
    }

    $before = "";
    $after = "";

    if(function_exists($f="_htag_$type")){
        $result = call_user_func($f, $attrs, $content);
        [$attrs, $content] = $result;
        $before = $result[2]??"";
        $after = $result[3]??"";
    }

    $attrs = htattrs($attrs);

    if(in_array($type,['input','img'])){
        return "<$type $attrs/>";
    }

    return "$before<$type $attrs>$content</$type>$after";
}


function _htag_a($attrs, $content){

    if(is_array($attrs['href']??0)){
        $attrs['href'] = htquery($attrs['href']);
    }

    $before = "";
    $after = "";

    if(isset($attrs['hotkey'])){
        if(!isset($attrs['id'])){
            $attrs['id'] = uniqid();
        }
        $after = htjsonkey($attrs['hotkey'])->click($attrs['id']);
    }

    return [$attrs, $content, $before, $after];
}
