<?php

$args = file_get_contents(".serv_args_".$_SERVER['SERVER_PORT']);
passthru("fcall ".$args);
