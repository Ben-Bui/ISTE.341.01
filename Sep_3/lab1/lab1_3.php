<!DOCTYPE html>
<html>
<head>
    <title>Question 3</title>
</head>
<body>
<?php

$scores = [87, 75, 93, 95];

// remove 2nd element (index 1)
unset($scores[1]);

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
