<?php


$out = htjsonkey('x')->click('element');

expect($out, "<script>document.addEventListener('keydown', function(e){ if(e.key === 'x'){ document.getElementById('element').click(); } })</script>");

$out = htjsonkey('x')->alert(5);

assert_contains($out, "alert(5)");

$out = htjsconfirm('Are you sure?')->visit('?delete=1');

assert_contains_in_order($out,[
    'confirm("Are you sure?")',
    'location.href="?delete=1"',
]);

$out = htjsconfirm('')->visit('?delete=1');
assert_not_contains($out,'confirm');
assert_contains($out,'location.href');
