<?php

require_once(__DIR__."/boot.php");

$table = $argv[1]??$tab['table'];

foreach($schema['relations'] as $rel){
    if($rel['child']==$table){
        echo "P $table.$rel[fk] -> $rel[parent]\n";
    }
}

foreach($schema['relations'] as $rel){
    if($rel['parent']==$table){
        echo "C $rel[child].$rel[fk] -> $table\n";
    }
}
