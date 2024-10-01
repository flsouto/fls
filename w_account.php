<?php
require_once(__DIR__.'/htform.php');
require_once(__DIR__.'/jsondb.php');

function w_account_form(){
    return [
        'data' => $_POST??[],
        'method' => 'POST',
        'fields' => [
            'email' => [
                'type' => 'email',
            ],
            'password' => [
                'type' => 'password'
            ]
        ]
    ];
}

function w_account(array $options){

    if(!session_id()) session_start();

    $home_url = $options['home']??'/';

    if(isset($_SESSION['uid'])){
        header("location:$home");
        return;
    }

    $file = $options['save_as'] ?? 'account_%s.json';
    $driver = $options['db_driver'] ?? 'jsondb';
    $is_signup = !empty($_GET['signup']);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $id = str_replace(['@','.'],['_at_','_'],$email);
        $user = $driver(sprintf($id));

        if($is_signup){
            if(!empty($user['id'])){
                // error: this user already exists
            } else {
                $user['id'] = $id;
                $user['email'] = $email;
                $user['password'] = $password;
                $user->save();
                $_SESSION['uid'] = $id;
                header("location:$home");
                return;
            }
        } else {
            if($user['password'] && $user['password'] == $password){
                $_SESSION['uid'] = $id;
            } else {
                header("location:$home");
                return;
            }
        }
    }

    $form = wc_account_form();

    if($is_signup){
        $form['button'] = 'Sign up';
        $form['action'] = '?signup=1';
    } else {
        $form['button'] = 'Sign in';
        $form['action'] = '?';
    }

    echo htform($form);

    $toggler = $options['toggler'];

    if($toggler){
        if(!$is_signup){
            echo htag('a', ['href'=>'?signup=1'], $options['toggler_signup']??"Sign up instead");
        } else {
            echo htag('a', ['href'=>'?'], $options['toggler_signin']??"Sign in instead");
        }
    }
}
