<?php

require_once(__DIR__."/jsondb.php");
require_once(__DIR__."/htform.php");
require_once(__DIR__."/redirect.php");
require_once(__DIR__."/w_msg.php");

function w_crud_form(array $params){

    $db = jsondb($params['store']);
    if(($_SERVER['REQUEST_METHOD']??'') != 'POST'){
        if($_GET['success']??0){
            w_success_msg_set("Data saved successfully");
        }
        $data = $db[$_GET['id']??null] ?? [];
    } else {
        $data = [];
        $fields = htform_parse_fields($params['fields']??[]);
        w_error_msg_set('');
        w_success_msg_set('');
        foreach($fields as $k => $spec){
            if(isset($_POST[$k])){
                $data[$k] = $_POST[$k];
            }
            $handler = $spec['func'].'_handle';
            if(!w_error_msg_get() && function_exists($handler)){
                $result = $handler($spec['attrs'], $data);
                if($result['error']??0){
                    w_error_msg_set($result['error']);
                }
                if($result['value']??0){
                    $data[$k] = $result['value'];
                }
            }
        }

        if(!w_error_msg_get()){

            if($id=($_GET['id']??null)){
                $db[$id] = array_merge($db[$id]??[], $data);
            } else {
                $id = uniqid();
                $db[$id] = $data;
            }

            $db->save();

            w_success_msg_set("Data saved successfully");

            if($callback=($params['success']??null)){
                $callback($id, $data, $db);
            } else {
                return redirect(['id' => $id,'success'=>1]);
            }
        }
    }

    $html[] = w_success_msg();
    $html[] = w_error_msg();

    $html[] = htform([
        'data' => $data,
        'method' => 'POST',
        'fields' => $params['fields']??[],
        'button' => $params['button'] ?? 'Save'
    ]);

    return implode($html);

}
