<?php
    require_once("lab3_3.php");

    //Instantiate BritishPerson object, set heigh and weight
    $britishPerson = new BritishPerson();
    $britishPerson->setHeight(175);
    $britishPerson->setWeight(62);

    //print out message with name and bmi
    $bmi = $britishPerson->calculateBMI();
    echo "<p>{$britishPerson->getFName()} {$britishPerson->getLName()} has a BMI of " . number_format($bmi, 2) . "</p>";
?>