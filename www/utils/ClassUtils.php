<?php

use CodeInc\StripAccents\StripAccents;

function class_to_array($class, $properties)
{
    $values = [];
    foreach ($properties as $prop) {
        $encoded_prop = mb_convert_encoding($prop, "ISO-8859-1", "UTF-8");
        $values[$encoded_prop] = $class->$encoded_prop;
    }

    return $values;
}

function non_null_properties($class, $properties): array
{
    $props = [];
    foreach ($properties as $prop) {
        $encoded_prop = StripAccents::strip($prop);
        if ($class->$encoded_prop !== NULL) $props[] =  $prop;
    }

    return $props;
}
function encode_array(array $array, $encode_to = "ISO-8859-1", $encode_from = "UTF-8"){
    $props = [];

   
    foreach ($array as $key => $val) { 
        $current_enc = mb_detect_encoding($key);
        // if ($current_enc == "UTF-8")
        $props[mb_convert_encoding($key, $encode_to, $encode_from)] = $val;
    }


    return $props;
}

function wraparound_char(array $array, string $character){
    $props = [];
    foreach ($array as $item){
        array_push($props, $character . $item . $character);
    }
    return $props;
}

function remove_underscores_from_array($array): array
{
    $props = [];

    foreach ($array as $key => $val) { 
        $props[str_replace("_", " ", mb_convert_encoding($key, "ISO-8859-1", "UTF-8"))] = $val;
    }
    return $props;
}

function remove_spaces_from_array($array): array
{
    $props = [];

    foreach ($array as $val) 
        array_push($props,str_replace(" ", "_", $val)); 
    return $props;
}
function remove_accents_from_arraykey($array): array
{
    $props = [];

    foreach ($array as $key => $val) 
        $props[StripAccents::strip($key)] = $val; 
    return $props;
}

function remove_accents_from_array($array): array
{
    $props = [];

    foreach ($array as $val) 
        array_push($props, StripAccents::strip($val)); 
    return $props;
}

function replace_accents($val){
    $val = iconv('UTF-8','ASCII//TRANSLIT',$val);
    return $val;
}

function prepare_bindings($array): array
{
    $props = [];

    foreach(remove_spaces_from_array($array) as $item){
        array_push($props, replace_accents($item));
    }

    return $props;
}