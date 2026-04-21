<?php

require_once(__DIR__."/boot.php");

$ids = array_column($tab['data'],'id');

$select = 'self."'.$argv[1].'"';

$join = '';

$isjson = false;
if(strstr($argv[1],'.')){
    $props = explode('.', $argv[1]);
    $parent = null;
    foreach($schema['relations'] as $rel){
        if($rel['child'] == $tab['table'] && $rel['fk']==$props[0]){
            $parent = $rel['parent'];
            break;
        }
    }
    if($parent){
        $select = 'parent."'.$props[1].'"';
        $join = 'JOIN "'.$parent.'" parent ON parent.id=self."'.$props[0].'"';
    } else if(isjson($tab['table'],$props[0])) {
        $isjson = true;
        $select = 'self."'.$props[0].'"';
        foreach(array_slice($props,1) as $p){
            $select .= "->'$p'";
        }
    } else {
        die("Expression could not be resolved: $argv[1]\n");
    }
}

$result = qkv('SELECT self.id, '.$select.' FROM "'.$tab['table'].'" self '.$join.' WHERE self.id IN(?)', [$ids]);

foreach($tab['data'] as &$row){
    $value = &$result[$row['id']];
    if($isjson){
        $value = json_decode($value,true);
    } else {
        $value = decval($tab['table'], $argv[1], $value);
    }
    $row[$argv[1]] = $value;
}

$tab->save();

view();
