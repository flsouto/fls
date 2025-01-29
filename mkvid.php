<?php

function mkvid($aud, $img, $out='output.mp4', $fps=10){
    shell_exec("ffmpeg -y -t $(soxi -D '$aud') -r $fps -i $img -c:v libx264 -pix_fmt yuv420p -crf 23 -r $fps -y /tmp/frames.mp4");
    shell_exec("ffmpeg -y -i /tmp/frames.mp4 -i $aud -c copy -map 0:v:0 -map 1:a:0 $out -shortest");
}
