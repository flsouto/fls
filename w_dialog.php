<?php
require_once(__DIR__."/htag.php");

function w_dialog(array $attrs){
    foreach(['title','content','actions','style'] as $k){
        if(isset($attrs[$k])){
            ${$k} = $attrs[$k];
            unset($attrs[$k]);
        }
    }
    $_class = "w_dialog";
    if(isset($attrs['class'])){
        $_class .= ' '.$attrs['class'];
    }
    $attrs['class'] = $_class;

    $ht_actions = [];
    foreach(($actions??[]) as $name => $a_attrs){
        $ht_actions[] = htag('a', $a_attrs, $name);
    }

    $html = htag('div',$attrs,[
        htag('div',['class'=>'head'],[
            htag('div',['class'=>'title'], $title??"")
        ]),
        htag('div',['class'=>'body'],$content??""),
        htag('div',['class'=>'actions'],$ht_actions),
    ]);

    $html .= htag('style', [], $style ?? w_dialog_default_style());

    return $html;
}

function w_dialog_default_style(){

return "

.w_dialog {
  width: 80%;
  max-width: 600px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: #fff;
  overflow: hidden;
  font-family: Arial, sans-serif;
}

.w_dialog .head {
  background-color: #f5f5f5;
  padding: 10px 15px;
  border-bottom: 1px solid #ddd;
}

.w_dialog .head .title {
  font-size: 14px;
  font-weight: bold;
  color: #333;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.w_dialog .body {
  padding: 15px;
  text-align: center;
}

.w_dialog .body img {
  max-width: 100%;
  height: auto;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.w_dialog .actions {
  display: flex;
  justify-content: center;
  padding: 10px 15px;
  background-color: #f5f5f5;
  border-top: 1px solid #ddd;
}

.w_dialog .actions a {
  color: #007bff;
  text-decoration: none;
  font-size: 14px;
  padding: 5px 10px;
  border-radius: 4px;
}

.w_dialog .actions a:hover {
    opacity:.5
}

";

}
