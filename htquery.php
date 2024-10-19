<?php

function htquery(array $vars=[]){
    $vars = array_merge($_GET, $vars);
    return '?'.http_build_query($vars);
}
