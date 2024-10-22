<?php


$out = htattrs(['id'=>'main','class'=>'container','disabled'=>true]);

expect($out,'id="main" class="container" disabled');

$out = htattrs(['class' => [1,2,3]]);

expect($out, 'class="1 2 3"');

$attrs = ['class'=>'parent'];
$attrs = htattrs_add_class($attrs, 'child');
$out = htattrs($attrs);
expect($out, 'class="parent child"');

$attrs = ['class'=>'parent'];
$attrs = htattrs_add_class($attrs, 'child',true);
$out = htattrs($attrs);
expect($out, 'class="child parent"');


$out = htattrs(['style'=>['color'=>'red','background'=>'yellow']]);
expect($out, 'style="color:red;background:yellow;"');
