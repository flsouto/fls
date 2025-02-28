<?php

class AudPart extends Aud{

    /**
     * @var Aud
     */
    protected $parent;
    protected $offset;
    protected $length;

    function rebuild(){
        if($this->offset){
            $part1 = $this->parent->copy(0, $this->offset);
        } else {
            $part1 = Sampler::silence(0);
        }
        $part1->add($this->cut(0,$this->len()));
        $parent_len = $this->parent->len();
        $offset2 = $this->offset + $this->length;
        if($offset2 < $parent_len){
            $part2 = $this->parent->copy($offset2, $parent_len-$offset2);
            $part1->add($part2);
        }
        return $part1;
    }

    function sync(){
        $obj = $this->rebuild();
        shell_exec("mv {$obj->file} {$this->parent->file}");

    }

}
