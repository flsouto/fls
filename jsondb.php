<?php

class JsonDB extends ArrayObject{

    public $data = [];

    function __construct(public $file){
        if(file_exists($file)){
            $contents = file_get_contents($file);
            parent::__construct(json_decode($contents,true));
        }
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
