<?php

require_once(__DIR__."/jsondb.php");
require_once(__DIR__."/htform.php");
require_once(__DIR__."/w_error_msg.php");
require_once(__DIR__."/w_success_msg.php");

function w_entity_form(array $params){

    $fields = htform_parse_fields($params['fields']??[]);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $data = [];
        $rules = [];

        foreach($fields as $k => $spec){
            if(isset($_POST[$k])){
                $data[$k] = $_POST[$k];
            }
            if(isset($spec['rules'])){
                $rules[$k] = $spec['rules'];
            }
        }

        if($rules){
            $result = validate($rules, $data);
            if(!empty($result['error_msg'])){
                w_error_msg_set($result['error_msg']);
            }
        }

        $db = jsondb($params['store']);

        if($id=($_GET['id']??null)){
            $db[$id] = array_merge($db[$id]??[], $data);
        } else {
            $id = uniqid();
            $db[$id] = $data;
        }

        $db->save();
        w_success_msg("Data saved successfully");

        if($h=($params['on_success']??null)){
            $h([
                'id' => $id,
                'db' => $db,
                'data' => $data
            ]);
        } else {
            return redirect(htquery([
                'id' => $id
            ]));
        }
    }

    $html[] = w_success_msg();
    $html[] = w_error_msg();

    $html[] = htform([
        'action' => 'POST'
        'fields' => $fields,
        'button' => $params['button'] ?? 'Save'
    ]);

    return implode(array_filter($html));

}
