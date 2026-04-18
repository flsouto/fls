<?php

require_once(__DIR__."/db.php");
require_once(__DIR__."/utils.php");
require_once(__DIR__."/../jsondb.php");

$state = jsondb('data/state.json');

if($state['tid']??''){
    $tab = jsondb("data/t$state[tid].json");
}

$schema = jsondb("data/schema.json");
