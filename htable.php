<?php
require_once(__DIR__."/htag.php");
require_once(__DIR__."/htjs.php");
require_once(__DIR__."/replace_vars.php");
require_once(__DIR__."/take.php");

function htable(array $attrs){

    [$attrs, $data, $actions] = take($attrs,'data','actions');

    $ths = [];

    foreach(array_keys($data[0]??[]) as $col){
        $ths[] = htag('th', ucwords(str_replace('_',' ',$col)));
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
                [$btn_attrs, $href, $confirm] = take($action, 'href','confirm');
                $href = replace_vars($action['href'], $row);
                $buttons[] = htag('button',[
                    $label,
                    'onclick' => htjsconfirm($action['confirm']??'')->visit($href),
                    ...$btn_attrs
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
