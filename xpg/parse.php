<?php

require_once(__DIR__."/boot.php");

$input = array_unique(array_filter(explode("\n",file_get_contents("/tmp/input"))));

function grep($regex){
    global $input;
    preg_match_all("/$regex/", implode(' ',$input),$matches);
    $input = $matches[1];
}

foreach(str_split(getenv('p')) as $flag){
    switch($flag){
        case 'd':
            grep('\b(\d+)\b');
        break;
        case 'w':
            grep('\b(\w+)\b');
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

file_put_contents($data_dir.'/input.json', $js=jsonstr($input));

echo $js."\n";
