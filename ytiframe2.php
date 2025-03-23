<?php
require_once(__DIR__."/ytiframe.php");
require_once(__DIR__."/htag.php");

function ytiframe2($vid, $iframe2_url){
    $html = ytiframe($vid);

    $html.=htag('iframe', [
        'src' => $iframe2_url,
        'width' => '100%',
        'height' => '1000px',
    ]);
    $html.=htag('style','iframe{ width:100% !important; } input{display:none;} *{margin:0px;padding:0px;background:black;}');

    return $html;
}
