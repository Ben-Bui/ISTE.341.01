<!DOCTYPE html>
<html>
<head>
    <title>Question 2</title>
</head>
<body>
<?php

$scores = [87, 75, 93, 95];
$sum = 0;
$count = 0;

foreach($scores as $score){
    $sum += $score;
    $count++;
}

$average = $sum / $count;
echo "Average test score is $average.";
?>
</body>
</html>
