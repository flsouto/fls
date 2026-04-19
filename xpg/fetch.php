<?php

require_once(__DIR__."/boot.php");

$ids = array_column($tab['data'],'id');

$select = 'self."'.$argv[1].'"';

$join = '';

if(strstr($argv[1],'.')){
    [$col1,$col2] = explode('.', $argv[1]);
    $parent = null;
    foreach($schema['relations'] as $rel){
        if($rel['child'] == $tab['table'] && $rel['fk']==$col1){
            $parent = $rel['parent'];
            break;
        }
    }
    if($parent){
        $select = 'parent."'.$col2.'"';
        $join = 'JOIN "'.$parent.'" parent ON parent.id=self."'.$col1.'"';
    } else if(isjson($tab['table'],$col1)) {
        $select = 'self."'.$col1.'"->>\''.$col2.'\'';
    } else {
        die("Expression could not be resoved: $argv[1]");
    }
}

$result = qkv('SELECT self.id, '.$select.' FROM "'.$tab['table'].'" self '.$join.' WHERE self.id IN(?)', [$ids]);

foreach($tab['data'] as &$row){
    $value = &$result[$row['id']];
    $value = decval($tab['table'], $argv[1], $value);
    $row[$argv[1]] = $value;
}

$tab->save();

view();
