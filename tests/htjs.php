<?php


$out = htjsonkey('x')->click('element');

expect($out, "<script>document.addEventListener('keydown', function(e){ if(e.key === 'x'){ document.getElementById('element').click(); } })</script>");

$out = htjsonkey('x')->alert(5);

assert_contains($out, "alert(5)");
