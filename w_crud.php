<?php
require_once(__DIR__.'/w_crud_form.php');
require_once(__DIR__.'/htable.php');
require_once(__DIR__.'/take.php');
require_once(__DIR__.'/redirect.php');
require_once(__DIR__.'/jsondb.php');
require_once(__DIR__.'/htag.php');
require_once(__DIR__.'/htattrs.php');

function w_crud(array $options){
    [$attrs, $store, $fields] = take($options, 'store', 'fields');

    if(($_GET['rm']??0) && ($_GET['id']??0)){
        $db = jsondb($store);
        unset($db[$_GET['id']]);
        $db->save();
        return redirect([
            'rm' => null
        ]);
    }

    if(($_GET['add']??0) || ($_GET['edit']??0)){
        $content = w_crud_form([
            'store' => $store,
            'fields' => $fields,
            'success' => function($id){
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
        $content = htable([
            'data' => jsondb($store),
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
