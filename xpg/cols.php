<?php

require_once(__DIR__.'/boot.php');

foreach($schema['types'][$argv[1]??$tab['table']] as $k => $v){
    echo "- $k: $v\n";
}

