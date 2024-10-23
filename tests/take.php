<?php

[$arr, $a,$b] = take(['a'=>1,'b'=>2,'c'=>3,'d'=>4], 'a','b');

expect($a,1);
expect($b,2);
expect($arr, ['c'=>3,'d'=>4]);
