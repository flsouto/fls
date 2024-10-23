<?php
require_once(__DIR__.'/w_crud_form.php');
require_once(__DIR__.'/htable.php');
require_once(__DIR__.'/take.php');
require_once(__DIR__.'/redirect.php');
require_once(__DIR__.'/jsondb.php');
require_once(__DIR__.'/htag.php');
require_once(__DIR__.'/htattrs.php');

function w_crud(array $options){

    [$attrs, $store, $fields, $success, $delete] = take($options, 'store', 'fields', 'success', 'deleted');

    if(($_GET['rm']??0) && ($_GET['id']??0)){
        $db = jsondb($store);
        unset($db[$_GET['id']]);
        $db->save();
        if(is_callable($deleted)){
            $deleted();
        } else {
            return redirect([
                'rm' => null
            ]);
        }
    }


    if(($_GET['add']??0) || ($_GET['edit']??0)){
        $content = w_crud_form([
            'store' => $store,
            'fields' => $fields,
            'success' => function($id,$data,$db) use ($success){
                if(is_callable($success)){
                    $success($id,$data,$db);
                    return;
                }
                redirect([
                    'add' => null,
                    'edit' => null,
                    'hl' => $id
                ]);
            }
        ]);
        if(redirect_location()){
            return;
        }
    } else {
        $content = [];
        $content[] = htag('a.add',['href'=>['add'=>1]],'Add');

        $data = [];
        $fields = htform_parse_fields($fields);

        foreach(jsondb($store) as $row){
            foreach($row as $k => $v){
                $fattrs = $fields[$k]['attrs'] ?? [];
                if(isset($fields[$k]) && isset($fattrs['options'][$v])){
                    $row[$k] = $fattrs['options'][$v];
                }
            }
            $data[] = $row;
        }

        $content[] = htable([
            'data' => $data,
            'actions' => [
                'Edit' => [
                    'href' => ['edit'=>1, 'id'=>'{id}']
                ],
                'Delete' => [
                    'href' => ['rm'=>1,'id'=>'{id}'],
                    'confirm' => 'Are you sure?',
                    'style' => ['color'=>'red']
                ]
            ]
        ]);

    }

    $attrs = htattrs_add_class($attrs, 'w_crud', true);

    return htag('div', $attrs, $content);
}
