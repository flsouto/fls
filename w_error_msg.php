<?php

require_once(__DIR__."/htag.php");

function w_error_msg($msg=null){
    $msg = $msg??$GLOBALS['w_error_msg']??$_SESSION['w_error_msg']??'';
    if(!$msg) return '';
    return htag('div',[
        'class' => 'w_error_msg',
        'style' => 'background:red;color:yellow'
    ],$msg);
}

function w_error_msg_set($msg){
    $GLOBALS['w_error_msg'] = $msg;
}
