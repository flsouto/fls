<?php
require_once(__DIR__."/htag.php");
require_once(__DIR__."/b64_data_url.php");
require_once(__DIR__."/w_dialog.php");
require_once(__DIR__."/redirect.php");

function w_img_iter($ipt_glob){

    $imgs = glob($ipt_glob,GLOB_BRACE);

    if(empty($imgs)){
        return 'No files to iterate';
    }

    $index = $_GET['wii']??0;
    $img = $imgs[$index];

    if(isset($_GET['rm'])){
        unlink($img);
        return redirect("?wii=$index");
    }

    $total = count($imgs);

    $actions = [];

    

    return w_dialog([
        'class' => 'w_img_iter',
        'title' => "$img <br/> (".($index+1)." of $total)",
        'content' => htag('img',['src' => b64_data_url($img)]),
        'actions' => [
            'Remove' => [
                'href' => "?wii=$index&rm=1",
                'style' => 'color:red',
                'hotkey' => 'r'
            ],
            'Prev' => [
                'href' => '?wii='.($index-1 < 0 ? $total-1 : $index-1),
                'hotkey' => 'p',
            ],
            'Next' => [
                'href' => '?wii='.($index+1),
                'hotkey' => 'n'
            ],
        ]
    ]);

}
