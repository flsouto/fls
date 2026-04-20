<?php

require_once(__DIR__."/boot.php");

$data = &$tab['data'];
foreach($data as $i => &$row) $row['index'] = $i;
unset($row);

if(isset($argv[1])){
    if(ctype_digit($argv[1])){
        $found = false;
        foreach($tab['data'] as $row){
            if($row['index'] == $argv[1]){
                $data = qrow('SELECT * FROM "'.$tab['table'].'" WHERE id=?',[$row['id']]);
                $data = decrow($tab['table'], $data);
                $found = true;
                break;
            }
        }
        if(!$found){
            $data = [];
        }
    } else if(strstr($argv[1],':')) {
        [$k,$v] = explode(':', $argv[1]);
        $result = [];
        foreach($data as $row){
            $result[$row[$k]] = $row[$v];
        }
        $data = $result;
    } else {
        $data = array_column($data, $argv[1]);
        if(getenv('uniq')){
            $data = array_values(array_unique($data));
        }
    }
}

$json = jsonstr($data);

file_put_contents($data_dir.'/view.json', $json);

echo $json;

echo "\n";
