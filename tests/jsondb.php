<?php

$obj = jsondb($f="/tmp/jsondb-test.json");

$obj->k1 = 1;
$obj->k2 = 2;

expect($obj['k1'], 1);
expect($obj['k2'], 2);

$obj->save();

assert_file_exists($f);

$obj = jsondb($f);

expect($obj->k1, 1);
expect($obj->k2, 2);
expect($obj['k1'], 1);
expect($obj['k2'], 2);

