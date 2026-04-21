<?php
require_once(__DIR__."/boot.php");

function find(string $table, $terms, $columns=null): array {

    global $schema;

    if(!is_array($terms)){
        $terms = [$terms];
    }

    if($columns && !is_array($columns)){
        $columns = [$columns];
    }

    // If columns not provided, fetch them from information_schema
    if (empty($columns)) {
        $columns = array_keys($schema['types'][$table]);
    }

    if (!$columns || count($columns) === 0) {
        return [];
    }

    // Build WHERE conditions
    $conditions = [];
    $params = [];

    foreach ($columns as $i => $col) {
        foreach($terms as $term){
            $conditions[] = "\"$col\"::text ILIKE ?";
            $params[] = "%$term%";
        }
    }

    $sql = sprintf(
        'SELECT id FROM "%s" WHERE %s',
        $table,
        implode(' OR ', $conditions)
    );

    return query($sql, $params);
}

$search = [];
foreach(array_slice($argv,2) as $value){
    if($value == '@v'){
        $search = [...$search, ...jsondb($data_dir.'/view.json')->vals()];
    } else if ($value == '@i') {
        $search = [...$search, ...jsondb($data_dir.'/input.json')->vals()];
    } else {
        $search[] = $value;
    }
}; unset($value);


if(stristr($argv[1],'.')){
    [$table,$col] = explode('.',$argv[1]);
} else {
    $table = $argv[1];
    $col = null;
}


if(!isset($schema['types'][$table])){
    $matches = [];
    foreach($schema['types'] as $k=>$v){
        if(strtolower($table)==strtolower($k)){
            $matches = [$k];
            break;
        }
        if(stristr($k,$table)){
            $matches[] = $k;
        }
    }
    if(count($matches) > 1){
        die("No table '$table'. Found matches: ".implode(', ', $matches)."\n");
    }
    $table = $matches[0];
}

$rows = find($table,$search,$col);

if(empty($rows)){
    die("Nothing returned from search\n");
}

newtab($table, $rows, $argv[1].' '.substr(implode(' ',$search),0,30));

view();
