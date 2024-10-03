<?php

function redirect($location){
    header("location:$location");
    $GLOBALS['redirect_location'] = $location;
}

function redirect_location(){
    return $GLOBALS['redirect_location']??null;
}
