<?php

require_once(__DIR__."/htag.php");

$w_panel_title = "";

function w_panel_load($script){
    $ext = pathinfo($script,PATHINFO_EXTENSION);
    return $ext === 'php' ? require($script) : file_get_contents($script);
}

function w_panel(array $options){

    global $w_panel_title;

    $items = [];
    $content = "";
    $selected = $_GET['p']??'index';
    $loader = $options['loader'] ?? 'w_panel_load';

    foreach($options['menu']??[] as $route => $v){

        $li_attrs = ['data-route'=>$route];

        if(strstr($route,':')){
            [$route, $script] = explode(':', $route);
        } else {
            $script = "$route.php";
        }

        if($selected == $route){
            $content = $loader($script);
            $li_attrs['class'] = "current";
            $inner = htag("b", [], $v);
            $w_panel_title = $v;
        } else {
            $inner = htag('a', ['href'=>'?p='.$route], $v);
        }

        $items[] = htag('li',$li_attrs,$inner);

    }


    $html = htag('div',['class'=>'w_panel'],[
        htag('div',['class'=>'menu'],htag('ul', [], $items)),
        htag('div',['class'=>'content'], [
            htag('h1',[],$w_panel_title),
            htag('div',['class'=>'body'],$content)
        ])
    ]);

    $html.= htag('style', [], $options['style']??w_panel_default_style());

    return $html;

}

function w_panel_default_style(){
    return "
    /* Base styles */
    .w_panel {
      display: flex;
      flex-wrap: wrap;
    }

    .menu {
      flex: 1 1 200px; /* Make the menu take up at least 200px width */
      max-width: 200px; /* Limit maximum width */
      padding: 10px;
      background-color: #f0f0f0;
      min-height:500px;
    }

    .menu ul {
      list-style-type: none;
      padding: 0;
    }

    .menu li {
      margin: 5px 0;
    }

    .menu li a,
    .menu li b {
      text-decoration: none;
      font-weight: bold;
      color: #333;
    }

    .menu li.current b {
      color: #007BFF; /* Highlight current menu item */
    }

    .content {
      flex: 2 1 500px; /* Make content take up more space */
      padding: 10px;
      background-color: #fff;
    }
    .content h1{
        margin-top:0;
    }
    .content .body{
        padding-left:10px;
    }
    /* Responsive design */
    @media (max-width: 768px) {
      .w_panel {
        flex-direction: column; /* Stack menu and content vertically */
      }

      .menu,
      .content {
        flex: 1 1 100%; /* Both take full width */
        max-width: 100%;
      }
    }

    @media (max-width: 480px) {
      .menu li {
        font-size: 14px; /* Reduce font size for smaller screens */
      }

      .content h1 {
        font-size: 18px; /* Adjust header size */
      }
    }
";
}
