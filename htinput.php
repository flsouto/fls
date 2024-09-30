<?php

require_once('htattrs.php');

function htinput(array $attrs){
    $attrs = htattrs($attrs);
    return "<input $attrs />";
}
