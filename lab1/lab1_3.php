<html>
<head>
    <title>Question 3</title>
</head>
<body>
<?php

$scores = [87, 75, 93, 95];

unset($scores[1]);//delete the 2nd score

$sum = 0;
$count = 0;

foreach($scores as $score){//same thing with q2
    $sum += $score;//sum = 0 +87 +93 etc..
    $count++;//add 1 to the counter each time a score is process
}

$average = $sum / $count;//sum/3, because 75 deleted
echo "Average test score is $average.";
?>
</body>
</html>
