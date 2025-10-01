<?php
    //load paren Person class
    require_once("lab3_1.php");

    //inheret from Person
    class BritishPerson extends Person {
        
        //overide calculateBMI for metric
        function calculateBMI(){
            // Convert cm to inches 
            $heightInInches = $this->getHeight() * 0.393701;
            
            // Convert kg to pounds 
            $weightInPounds = $this->getWeight() * 2.20462;
            
            //user parent class bmi formual to with convert value
            return 705 * $weightInPounds / ($heightInInches * $heightInInches);
        }
    }

?>