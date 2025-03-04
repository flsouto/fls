<?php
require_once('htag.php');

function ytiframe(...$ids){
    $index = $_GET['index']??0;
    $vid_id = $ids[$index];

    $html = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$vid_id.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';

    if(count($ids)>1){
        $html .= '<hr/>';
        foreach($ids as $i=>$id){
            $html .= ($i == $index ? ' &raquo; ' : '' ) . htag('a',[
                'href' => '?index='.$i,
                'hotkey' => $i === $index+1 ? 'n' : ($i === $index-1 ? 'p' : null),
                $id
            ]) . "<br/>";
        }
    }

    return $html;
}
