<html>
<head>
    <title>Question 2</title>
</head>
<body>
<?php

$scores = [87, 75, 93, 95];//index array
$sum = 0;
$count = 0;

foreach($scores as $score){//loop the array to add the score to sum
    $sum += $score;//sum = 0 +87 +75 etc..
    $count++;//add 1 to the counter each time a score is process
}

$average = $sum / $count; //(sum)/4 or 350/4 = 87.5
echo "Average test score is $average.";//print out the average
?>
</body>
</html>
