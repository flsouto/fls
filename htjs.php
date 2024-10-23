<?php
require_once(__DIR__."/htquery.php");

class HtJs{

    const HOOK = '__JSCHAINHOOK__';

    function __construct(public $script = ''){}

    function __call($method, $args){
        $f = 'htjs'.$method;
        if(!function_exists($f)){
            throw new \Exception("Function not found: $f");
        }
        $output = call_user_func_array($f, $args);
        if($output instanceof self){
            $output = $output->script;
        }
        return new self(str_replace(self::HOOK, $output, $this->script));
    }

    function __toString(){
        return "<script>$this->script</script>";
    }

}

function htjs($script=''){
    return new HtJs($script);
}

function htjsonkey($key, $action=HtJs::HOOK){
    return htjs("document.addEventListener('keydown', function(e){ if(e.key === '$key'){ $action; } })");
}

function htjsclick($id){
    return htjs("document.getElementById('$id').click()");
}

function htjsalert($msg){
    return htjs("alert(".json_encode($msg).")");
}

function htjsconfirm($confirm='', $action=HtJs::HOOK){
    if($confirm){
        return htjs("if(confirm(".json_encode($confirm).")) { $action } ");
    }
    return htjs($action);
}

function htjsvisit($href){
    if(is_array($href)){
        $href = htquery($href);
    }
    return "location.href=".json_encode($href).";";
}
