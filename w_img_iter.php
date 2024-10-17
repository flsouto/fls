<?php
require_once(__DIR__."/htag.php");
require_once(__DIR__."/b64_data_url.php");

function w_img_iter($ipt_glob){

    $imgs = glob($ipt_glob);

    if(empty($imgs)){
        return 'No files to iterate';
    }

    $index = $_GET['wii']??0;
    $img = $imgs[$index];

    return htag('div',[],[
        htag('div',[],$img),
        htag('img',['src' => b64_data_url($img)]),
        htag('a',['href'=>'?wii='.($index+1)],'Next')
    ]);


}
