<?php

function redirect($location){
    if(!defined('fls_mock_call')){
        header("location:$location");
    } else {
        fls_mock_call();
    }
}
