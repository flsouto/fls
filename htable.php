<?php
require_once(__DIR__."/htag.php");
require_once(__DIR__."/htjs.php");
require_once(__DIR__."/replace_vars.php");

function htable(array $attrs){

    $data = [];
    $actions = [];

    foreach(['data','actions'] as $k){
        if(isset($attrs[$k])){
            ${$k} = $attrs[$k];
            unset($attrs[$k]);
        }
    }

    $ths = [];

    foreach(array_keys($data[0]??[]) as $col){
        $ths[] = htag('th', $col);
    }

    if($actions){
        $ths[] = htag('th',['colspan'=>count($actions)]);
    }

    $thead = htag('thead',$ths);
    $trs = [];

    foreach($data as $row){
        $tds = [];
        foreach($row as $k => $v){
            $tds[] = htag('td', [$v]);
        }
        $a_td = [];
        foreach($actions as $label => $action){
            if(is_callable($action)){
                $a_td[] = $action($row);
            } else {
                $href = replace_vars($action['href'], $row);
                $a_td[] = htag('button',[
                    $label,
                    'onclick' => htjsconfirm($button['confirm']??'')->visit($button['href'])
                ]);
            }
        }
        if($a_td){
            $tds[] = htag('td',$a_td);
        }
        $trs[] = htag('tr', $tds);
    }

    $tbody = htag('tbody',$trs);

    return htag('table', $attrs, [
        $thead,
        $tbody
    ]);

}
