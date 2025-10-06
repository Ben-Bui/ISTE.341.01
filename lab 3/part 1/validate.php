<?php

//check if string is valid
function validateString($str, $maxLength = 100) {
    //remove extra space, and check if empty and not too long
    return !empty(trim($str)) && strlen(trim($str)) <= $maxLength;
}
//Check if date box is not empty
function validateDateInput($date) {
    return !empty(trim($date));
}
//clean up user input 
function sanitizeString($data) {
    $data = trim($data);//remove extra space
    $data = stripslashes($data);//remove backslashes
    return $data;
}

?>