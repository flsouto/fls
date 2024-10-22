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

        $buttons = [];
        foreach($actions as $label => $action){
            if(is_callable($action)){
                $buttons[] = $action($row);
            } else if(isset($action['href'])) {
                $href = replace_vars($action['href'], $row);
                $onclick = htjsconfirm($action['confirm']??'')->visit($href)->script;
                $buttons[] = htag('button',[
                    $label,
                    'onclick' => $onclick
                ]);
            } else {
                $buttons[] = $action;
            }
        }
        if($buttons){
            $tds[] = htag('td',$buttons);
        }
        $trs[] = htag('tr', $tds);
    }

    $tbody = htag('tbody',$trs);

    $attrs = htattrs_add_class($attrs, 'htable', true);

    return htag('table', $attrs, [
        $thead,
        $tbody
    ]);

}
