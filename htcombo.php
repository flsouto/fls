<?php

require_once('htattrs.php');

function htcombo($attrs = []){
    $value = $attrs['value']??null;
    $options = $attrs['options']??[];
    unset($attrs['value'],$attrs['options']);
    $attrs = htattrs($attrs);
    echo "<select $attrs>";
    foreach($options as $label => $id){
        $selected = $id == $value ? ' selected' : '';
        echo "<option value=\"$id\"$selected>$label</option>";
    }
    echo "</select>";
};
