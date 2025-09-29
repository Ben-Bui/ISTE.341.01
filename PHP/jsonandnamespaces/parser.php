<?php

$decoded = json_decode($_POST['json']); //converts a json string to a php object
var_dump($_POST['json']);
var_dump($decoded);

$hobbies = "";
foreach($decoded->hobby as $v){
    if ($v->isHobby){
        $hobbies .= $v->hobbyName.",";
    }
}

$hobbies = trim($hobbies,",");

//create response object
$json = [];
$json['sent'] = ["name"=>$decoded->firstname, "email"=>$decoded->email,
                "hobbies"=>$hobbies];
$json['errorsNum'] = 2;
$json['error'] = [];
$json['error'] [] = 'Wrong email';
$json['error'] [] = 'Wrong hobby';
var_dump($json);

die(json_encode($json));
?>