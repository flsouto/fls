<?php

function fcall($file, $args){

    $ext = pathinfo($file, PATHINFO_EXTENSION);

    if(empty($ext)){
        foreach(['php','py','js'] as $ext){
            if(stream_resolve_include_path("$file.$ext")){
                $file = "$file.$ext";
                break;
            }
        }
    }

    switch($ext){
        case 'php':
            require $file;
            $func = str_replace(".$ext","",$file);
            if($args[0] === '-j'){
                $args = array_map(fn($arg) => json_decode($arg,true), array_slice($args,1));
            }
            $result = $func(...$args);
            if(is_array($result)){
                $result = implode("\n", $result);
            }
            echo $result;
        break;
        default:
            echo "No fcall handler for file: $file\n";
            exit(1);
    }

}
