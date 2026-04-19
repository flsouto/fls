<?php

require_once(__DIR__."/boot.php");

foreach($schema['types'] as $table => $cols){
    echo "- $table\n";
}
