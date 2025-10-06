<?php
    require_once("lab3_1.php");

    //instantiate person object, set height and weight
    $person = new Person();
    $person->setHeight(72);
    $person->setWeight(180);

    //print message 
    $bmi = $person->calculateBMI();
    echo "<p>{$person->getFName()} {$person->getLName()} has a BMI of " . number_format($bmi, 2) . "</p>";
?>