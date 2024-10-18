<?php

foreach(glob(__DIR__."/*.php") as $lib){
    if(in_array(basename($lib),['flib.php','demo_router.php','serv_router.php'])) continue;
    require_once $lib;
}
