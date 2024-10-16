<?php

function ytiframe($vid_id){
    return '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$vid_id.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
}
