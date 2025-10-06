<?php
class Person {

    private $last, $first;

    function __construct($lastName = "TBD", $firstName = "TBD"){
        $this->last = $lastName;
        $this->first = $firstName;
    }

    function getFirstName(){return $this->first; }
    function getLastName(){return $this->last; }

    function sayHello() {
        return "Hi there! My first name is {$this->first} 
            my last name is {$this->getLastName()}<br />";
    }
}