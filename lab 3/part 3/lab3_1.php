<?php

class Person {
    //private prop, restricitve access
    private $fName;
    private $lName;
    private $height;
    private $weight;

    //constructor, run when create new person
    function __construct($fName = "Sam", $lName = "Spade"){
        $this->fName = $fName;
        $this->lName = $lName;
    }

    // Accessors allow outside code to read private
    function getFName(){
        return $this->fName;
    }

    function getLName(){
        return $this->lName;
    }

    function getHeight(){
        return $this->height;
    }

    function getWeight(){
        return $this->weight;
    }

    // Mutators, can modify from outside private properties
    function setFName($fName){
        $this->fName = $fName;
    }

    function setLName($lName){
        $this->lName = $lName;
    }

    function setHeight($height){
        $this->height = $height;
    }

    function setWeight($weight){
        $this->weight = $weight;
    }

    //calculate bmi
    function calculateBMI(){
        //BMI= 705 x weight/(heigh x height)
        return 705 * $this->weight / ($this->height * $this->height);
    }
}

?>