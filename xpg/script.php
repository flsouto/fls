<?php require_once(__DIR__."/../utils.php"); $data = json_decode(file_get_contents(__DIR__."/view.json"), true);

foreach($data as $k => $v){
    print_r($v);
}
