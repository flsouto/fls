<?php
require_once(__DIR__.'/htform.php');
require_once(__DIR__.'/jsondb.php');
require_once(__DIR__.'/redirect.php');
function w_account_form(){
    return [
        'data' => $_POST??[],
        'method' => 'POST',
        'fields' => [
            'email' => [
                'type' => 'email',
                'placeholder' => 'E-mail'
            ],
            'password' => [
                'type' => 'password',
                'placeholder' => 'Password'
            ]
        ]
    ];
}

function w_account_handle(array $options){

    global $error_msg;

    $error_msg = "";

    if(($_SERVER['REQUEST_METHOD']??'') !== 'POST'){
        return;
    }

    $email = trim($_POST['email']??'');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error_msg = "Invalid email: $email";
        return;
    }

    $store = $options['store'] ?? 'account_%s.json';

    $id = str_replace(['@','.'],['_at_','_'],$email);
    $user = jsondb(sprintf($store,$id));

    $is_signup = !empty($_GET['signup']);
    $home_url = $options['home_url']??'/';

    $password = trim($_POST['password']??'');

    if($is_signup){

        if(strlen($password) < 5){
            $error_msg = "Password too short: $password";
            return;
        }

        if(!empty($user['id'])){
            $error_msg = "An account with this email already exists";
        } else {
            $user['id'] = $id;
            $user['email'] = $email;
            $user['password'] = $password;
            $user->save();
            $_SESSION['uid'] = $id;
            redirect($home_url);
            return;
        }
    } else {
        if(!empty($user['password']) && $user['password'] == $password){
            $_SESSION['uid'] = $id;
            redirect($home_url);
            return;
        } else {
            $error_msg = "Invalid credentials";
        }
    }
}

function w_account(array $options){

    global $error_msg;

    if(!session_id()) session_start();

    $home_url = $options['home_url']??'/';

    if(isset($_GET['logout'])){
        unset($_SESSION['uid']);
    }

    if(isset($_SESSION['uid'])){
        redirect($home_url);
        return;
    }

    $html = [];

    w_account_handle($options);

    if(!empty($error_msg)){
        $html[] = htag('div',['class'=>'error_msg'], $error_msg);
    }

    $is_signup = !empty($_GET['signup']);

    $form = w_account_form();

    if($is_signup){
        $form['button'] = 'Sign up';
        $form['action'] = '?signup=1';
    } else {
        $form['button'] = 'Sign in';
        $form['action'] = '?';
    }

    $html[] = htform($form);

    $hide_toggler = $options['hide_toggler']??false;

    if(!$hide_toggler){
        if(!$is_signup){
            $html[] = htag('a', ['href'=>'?signup=1'], "Sign up instead");
        } else {
            $html[] = htag('a', ['href'=>'?'], "Sign in instead");
        }
    }

    return implode($html);
}

