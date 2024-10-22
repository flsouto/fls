<?php

$out = replace_vars("My name is {name} and I am {age} years old.",[
    'name' => 'Bob',
    'age' => 6
]);

assert_contains_in_order($out, ["is Bob","6 years"]);
