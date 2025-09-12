<?php

spl_autoload_register(function($class){
    require_once "./classes/$class.class.php";
});

echo "<h2>Static Usage</h2>";

$number1 = "one";
$number2 = 23456;
$number3 = "2";

$yesNo = (Validator::numeric($number1)) ? "Yes" : "No";
echo "<p>$number1 is a number? $yesNo</p>";

$yesNo = (Validator::numeric($number2)) ? "Yes" : "No";
echo "<p>$number2 is a number? $yesNo</p>";

$yesNo = (Validator::numeric($number3)) ? "Yes" : "No";
echo "<p>$number3 is a number? $yesNo</p>";

echo "<h2> Regular Usage</h2>";
$person1 = new Person("Smith", "Bob");
$person2 = new Person();
$person3 = new Person("Jones");

echo "<p> Person 1 :{$person1->sayHello()}</p>";
echo "<p> Person 2 :{$person2->sayHello()}</p>";
echo "<p> Person 3 : My last name is {$person3->getLastname()} and
        my first name is {$person3->getFirstName()}</p>";


?>
