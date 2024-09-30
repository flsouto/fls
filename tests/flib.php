<?php

foreach(['input','htcombo'] as $f){
    info("checking function $f");
    assert_true(function_exists($f));
}
