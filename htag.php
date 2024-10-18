<?php
require_once(__DIR__."/htattrs.php");
function htag($type, $attrs=[], $content=null){
    if(is_array($content)){
        $content = implode($content);
    }
    $attrs = htattrs($attrs);
    if(in_array($type,['input','img'])){
        return "<$type $attrs/>";
    }
    return "<$type $attrs>$content</$type>";
}
