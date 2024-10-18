<?php
require_once(__DIR__."/b64_data_url.php");
require_once(__DIR__."/w_arr_iter.php");
require_once(__DIR__."/redirect.php");

function w_img_iter($ipt_glob){

    $imgs = glob($ipt_glob,GLOB_BRACE);

    if(empty($imgs)){
        return 'No files to iterate';
    }

    return w_arr_iter($imgs, [
        'remove' => fn($img) => unlink($img),
        'render' => fn($img) => htag('img',['src' => b64_data_url($img)]),
        'class' => 'w_img_iter'
    ]);

}
