<html>
<head>
    <title>Question 4</title>
</head>
<body>
<?php
echo "<h2>4a</h2>";
$array = [
    [1,2,3,4,5],//array 0/index 0
    [6,7,8,9,10],//array 1/ index 1
    [11,12,13]//array 2/ index 2
];

echo $array[1][2];//2nd row third number

echo "<h2>4d</h2>";
$array[2][] = 14;//append to the end
$array[] = [15,16,17];//append new stuff to array, new rows index 3
$array[] = 18;//append to the end, index 4

foreach($array as $i=>$row){//go through each row of array
    if (is_array($row)) {//check the the row an array since 0-3 is but not 4
        foreach($row as $j=>$val){//if row an array
            echo "[$i][$j]: $val ";//print i=index and j = inner index val= the actual number
        }
    } else {
        echo "[$i]: $row ";//if not an array
    }
    echo "<br/>";
}

echo "<h2>4e</h2>";
for($i=0; $i<count($array); $i++){//use count to know how many thing in the array
    if(is_array($array[$i])){//if array
        for($j=0; $j<count($array[$i]); $j++){
            echo "[$i][$j]: ".$array[$i][$j]." ";
        }
    } else {
        echo "[$i]: ".$array[$i];//if not array
    }
    echo "<br/>";
}

echo "<h2>4g</h2>";
$array2 = [//array 2 have name and address
    "name" => ["first"=>"Gia", "last"=>"Bui"],//array2 [name] contain first and last name
    "address" => [//array2 [address] contain everything of the address
        "street"=>"123 Main St",
        "city"=>"Rochester",
        "state"=>"NY",
        "zip"=>"14623"
    ]
];

foreach($array2 as $key => $subarray) {//key = name or address
    foreach($subarray as $subkey => $value) {//for name print first and last, for address print the addresss stuff
        echo "[$key][$subkey]: $value ";
    }
    echo "<br/>";
}

echo "<h2>4i</h2>";
$array2["name"]["middle"]="none";
$array2["name"][] = ["my"=>"name"];//append another array with key my to [name]
$array2["name"][] = 25;// 1=>25
$array2[] = [1,2,3,4,5];//append new element to top array2
$array2[][] = ["testing"=>"yes"];

foreach($array2 as $key=>$row){
    if(is_array($row)){//check if array
        foreach($row as $subkey=>$subval){//check if inner valye is array
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
