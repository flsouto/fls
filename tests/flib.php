#!/bin/php
<?php

require(__DIR__.'/../flib.php');

foreach(['input','htcombo'] as $f){
    if(!function_exists($f)){
        echo "Function not found: $f\n";
        exit(1);
    }
}
