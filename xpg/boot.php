<?php

require_once(__DIR__."/db.php");
require_once(__DIR__."/utils.php");
require_once(__DIR__."/../jsondb.php");

$data_dir = getenv('XPG_DATA_DIR') ?: 'data';

$state = jsondb($data_dir.'/state.json');

if($state['tid']??''){
    $tab = jsondb($path=$data_dir."/t$state[tid].json");
}

$schema = jsondb($data_dir."/schema.json");
