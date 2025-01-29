<?php

class JsonDB extends ArrayObject{

    function __construct(public $file){
        if(file_exists($file)){
            $contents = file_get_contents($file);
            parent::__construct(json_decode($contents,true));
        }
    }

    function keys(){
        return array_keys($this->getArrayCopy());
    }

    function fstk(){
        $keys = $this->keys();
        return $keys[0] ?? null;
    }

    function lstk(){
        $keys = $this->keys();
        $key = end($keys);
        return $key;
    }

    function gitpush($msg='updates'){
        shell_exec("git add $this->file; git commit -m '$msg'; git push origin $(git branch --show-current)");
    }

    function __set($k,$v){
        $this[$k] = $v;
    }

    function __get($k){
        return $this[$k]??null;
    }

    function save(){
        file_put_contents($this->file, json_encode($this->getArrayCopy(), JSON_PRETTY_PRINT));
    }
}

function jsondb($path){
    return new JsonDB($path);
}
