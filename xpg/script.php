<?php require_once(__DIR__."/../boot.php"); $data = jsondb(__DIR__."/view.json");

foreach($data as $k => $v){
    print_r($v);
}
