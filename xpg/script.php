<?php require_once(__DIR__."/../boot.php"); $data = jsondb($data_dir."/view.json");

foreach($data as $k => $v){
    print_r($v);
}
