<?php

function ytiframe(...$ids){
    $vid_id = $ids[$_GET['index']??0];

    $html = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$vid_id.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';

    if(count($ids)>1){
        $html .= '<hr/>';
        foreach($ids as $i=>$id){
            $html .= ($i == ($_GET['index']??0) ? ' &raquo; ' : '' ) . "<a href='?index=$i'>$id</a><br/>";
        }
    }

    return $html;

    return $html;
}
