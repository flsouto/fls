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

function htag_find_by_text($html, $text, $attr=null){
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $nodes = $xpath->query("//*[contains(text(), '$text')]");

    if ($nodes->length > 0) {
        $element = $nodes->item(0);
        if($attr){
            if($element->attributes[$attr] ?? null){
                return $element->attributes[$attr]->textContent;
            }
            return null;
        }
        return $element;
    }

    return null;
}
