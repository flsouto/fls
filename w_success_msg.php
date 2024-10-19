<?php

require_once(__DIR__."/htag.php");

function w_success_msg($msg=null){
    $msg = $msg??$GLOBALS['w_success_msg']??$_SESSION['w_success_msg']??'';
    if(!$msg) return '';
    return htag('div',[
        'class' => 'w_success_msg',
        'style' => 'background:honeydew;color:green'
    ],$msg);
}

function w_success_msg_set($msg){
    $GLOBALS['w_success_msg'] = $msg;
}

function w_success_msg_get(){
    return $GLOBALS['w_success_msg']??null;
}
