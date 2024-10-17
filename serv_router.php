<?php

$args = file_get_contents(".serv_args_".$_SERVER['SERVER_PORT']);
$argv = preg_split('/\s+(?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/', trim($args));

$argv = array_map(function ($arg) {
    return trim($arg, '"');
}, $argv);

require_once(__DIR__.'/fcall.php');
fcall($argv[0], array_slice($argv,1));
