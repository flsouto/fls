<?php
require_once(__DIR__."/htquery.php");

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
