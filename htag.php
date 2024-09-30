<?php
require_once(__DIR__."/htattrs.php");
function htag($type, $attrs=[], $content){
    if(is_array($content)){
        $content = implode($content);
    }
    $attrs = htattrs($attrs);
    return "<$type $attrs>$content</$type>";
}
