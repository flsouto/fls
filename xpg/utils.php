<?php

function jsonstr($data){

    $json = json_encode($data,JSON_PRETTY_PRINT ^ JSON_UNESCAPED_UNICODE);

    $json = preg_replace_callback('/^( +)/m', function ($matches) {
        return str_repeat(' ', strlen($matches[1]) / 2);
    }, $json);

    return $json;
}

function decval($table, $column, $value){
    global $schema;
    if(isjson($table, $column)){
        $value = json_decode($value,true);
    }
    return $value;
}

function decrow($table, $row){
    foreach($row as $k => &$v){
        $v = decval($table, $k, $v);
    }
    return $row;
}

function decrows($table, $rows){
    return array_map(fn($row) => decrow($table, $row), $rows);
}

function view(){
    passthru('php '.__DIR__.'/view.php');
}

function newtab($table,$rows,$name){
    global $state,$data_dir;
    $state['tid'] = $tid = count($state['tabs']??[])+1;
    $state['tabs'][] = [
        'id' => $tid,
        'name' => $name,
        'count' => count($rows)
    ];
    $state->save();

    $tab = jsondb("$data_dir/t$tid.json");
    $tab['id'] = $tid;
    $tab['name'] = $name;
    $tab['table'] = $table;
    $tab['data'] = $rows;
    $tab->save();
    return $tab;
}

function isjson($table,$col){
    global $schema;
    return str_starts_with($schema['types'][$table][$col]??'', 'json');
}

function findtbl($table){

    global $schema;

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
        echo "Resolved table: $table\n";
    }
    return $table;

}
