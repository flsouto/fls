<?php
require_once(__DIR__."/htattrs.php");
require_once(__DIR__."/htquery.php");

function htag($type, $attrs=[], $content=null){
    if(is_array($content)){
        $content = implode($content);
    }
    if($type == 'a' && is_array($attrs['href']??0)){
        $attrs['href'] = htquery($attrs['href']);
    }
    $attrs = htattrs($attrs);
    if(in_array($type,['input','img'])){
        return "<$type $attrs/>";
    }
    return "<$type $attrs>$content</$type>";
}
