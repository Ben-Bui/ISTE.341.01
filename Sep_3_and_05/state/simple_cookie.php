<?php

$expire = time()+10; // 10 seconds from script running
$path="/~btb4516/";
$domain="solace.ist.rit.edu";
$secure = false; //best pratices should be true

//before any output from your script
setcookie("test_cookie","arrgh!",$expire, $path, $domain,$secure);

$counter = $_COOKIE['counter'];
$counter++;
setcookie('counter',$counter,$expire,$path,$domain,$secure);

$getCounter = $_COOKIE['counter'];

echo "<h2>counter=$counter</h2>";
echo "<h2>\$_COOKIE variables</h2>";
foreach($_COOKIE as $k=>$v) {
    echo "$k=$v<br />";
}
?>