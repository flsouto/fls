<?php

$out = replace_vars("My name is {name} and I am {age} years old.",[
    'name' => 'Bob',
    'age' => 6
]);

assert_contains_in_order($out, ["is Bob","6 years"]);


$out = replace_vars([
    'name' => '{name}',
    'age' => '{age} years',
],[
    'name' => 'Joe',
    'age' => '66'
]);


expect($out['name'], 'Joe');
expect($out['age'], '66 years');
