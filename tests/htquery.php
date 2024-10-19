<?php

$_GET['y'] = 1;

$q = htquery(['x'=>1,'z'=>0]);

assert_contains($q, 'y=1');
assert_contains($q, 'x=1');
assert_contains($q, 'z=0');
assert_contains($q, '?');
assert_contains($q, '&');

$q = htquery(['x'=>1,'y'=>null]);

assert_not_contains($q, 'y');
