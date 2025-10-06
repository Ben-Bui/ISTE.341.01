<?php
class Validator {
    public static function numeric($value) {
        $reg = "/^[0-9]+$/";
        return (bool) preg_match($reg, $value);
    }
}
