<?php

foreach(glob(__DIR__."/*.php") as $lib){
    if(basename($lib) === 'flib.php') continue;
    require $lib;
}
