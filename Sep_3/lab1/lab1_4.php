<!DOCTYPE html>
<html>
<head>
    <title>Question 4</title>
</head>
<body>
<?php
echo "<h2>4a</h2>";
$array = [
    [1,2,3,4,5],
    [6,7,8,9,10],
    [11,12,13]
];

echo "The element with value of 8: ".$array[1][2]." and its indexes are: [1][2]";

echo "<h2>4c and 4d - foreach loops</h2>";
$array[2][] = 14;
$array[] = [15,16,17];
$array[] = 18;

foreach($array as $i=>$row){
    if (is_array($row)) {
        foreach($row as $j=>$val){
            echo "[$i][$j]: $val ";
        }
    } else {
        echo "[$i]: $row ";
    }
    echo "<br/>";
}

echo "<h2>4e - for loops</h2>";
for($i=0; $i<count($array); $i++){
    if(is_array($array[$i])){
        for($j=0; $j<count($array[$i]); $j++){
            echo "[$i][$j]: ".$array[$i][$j]." ";
        }
    } else {
        echo "[$i]: ".$array[$i];
    }
    echo "<br/>";
}

echo "<h2>4f and 4g</h2>";
$array2 = [
    "name" => ["first"=>"Bryan", "last"=>"French"],
    "address" => [
        "street"=>"123 Main St",
        "city"=>"Rochester",
        "state"=>"NY",
        "zip"=>"14623"
    ]
];

foreach($array2 as $key=>$row){
    foreach($row as $subkey=>$val){
        echo "[$key][$subkey]: $val ";
    }
    echo "<br/>";
}

echo "<h2>4h and 4i</h2>";
$array2["name"]["middle"]="none";
$array2["name"][] = ["my"=>"name"];
$array2["name"][] = 25;
$array2[] = [1,2,3,4,5];
$array2[][] = ["testing"=>"yes"];

foreach($array2 as $key=>$row){
    if(is_array($row)){
        foreach($row as $subkey=>$subval){
            if(is_array($subval)){
                foreach($subval as $deepkey=>$deepval){
                    echo "[$key][$subkey][$deepkey]: $deepval ";
                }
            } else {
                echo "[$key][$subkey]: $subval ";
            }
        }
    } else {
        echo "[$key]: $row ";
    }
    echo "<br/>";
}
?>
</body>
</html>
