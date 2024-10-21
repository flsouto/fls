<?php
require_once(__DIR__."/htquery.php");
require_once(__DIR__."/w_msg.php");

function redirect($location){
    if(is_array($location)){
        $location = htquery($location);
    }
    header("location:$location");
    $GLOBALS['redirect_location'] = $location;
}

function redirect_location(){
    return $GLOBALS['redirect_location']??null;
}
