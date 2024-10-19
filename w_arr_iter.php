<?php
require_once(__DIR__."/w_dialog.php");

function w_arr_iter($array, array $options){

    $render = $options['render'] ?? fn($item) => $item;
    $remove = $options['remove'] ?? false;
    $class = $options['class'] ?? 'w_arr_iter';
    $title = $options['title'] ?? fn($item, $total, $index) =>  "$item <br/> ($index of $total)";

    if(empty($array)){
        return 'No elements to iterate';
    }

    $index = $_GET['i']??0;

    if(!isset($array[$index])){
        return redirect(['i'=>0]);
    }

    $item = $array[$index];

    if($remove && isset($_GET['rm'])){
        $remove($item);
        return redirect(['rm'=>null, 'i' => isset($array[$index+1]) ? $index : 0 ]);
    }

    $total = count($array);

    $actions = [];

    if($remove){
        $actions['Remove'] = [
            'href' => ['i'=>$index,'rm'=>1],
            'style' => 'color:red',
            'hotkey' => 'r'
        ];
    }

    $actions = [
        ...$actions,
        'Prev' => [
            'href' => ['i' => $index-1 < 0 ? $total-1 : $index-1 ],
            'hotkey' => 'p',
        ],
        'Next' => [
            'href' => ['i' => $index+1 >= $total ? 0 : $index+1 ],
            'hotkey' => 'n'
        ],
    ];

    return w_dialog([
        'class' => $class,
        'title' => $title($item, $total, $index+1),
        'content' => $render($item),
        'actions' => $actions
    ]);
}
