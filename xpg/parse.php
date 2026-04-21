<?php

require_once(__DIR__."/boot.php");

$input = array_unique(array_filter(explode("\n",file_get_contents("/tmp/input"))));

function grep($regex){
    global $input;
    $result = [];
    foreach($input as $line){
        preg_match_all("/$regex/", $line, $matches);
        if(!empty($matches[1])){
            $result = [...$result, ...$matches[1]];
        }
    }
    $input = $result;
}

foreach(str_split(getenv('p')) as $flag){
    switch($flag){
        case 'd':
            grep('\b(\d+)\b');
        break;
        case 'q':
            grep('["\'](.*?)["\']');
        break;
        case 'u':
            grep('([0-9a-fA-F\-]{36})');
        break;
    }
}

$input = array_filter(array_unique($input));

if($delete=getenv('d')){
    $input = array_diff($input,explode(',',$delete));
}

file_put_contents($data_dir.'/input.json', $js=jsonstr(array_values($input)));

echo $js."\n";
