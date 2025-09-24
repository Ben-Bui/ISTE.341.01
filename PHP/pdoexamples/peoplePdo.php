<?php

require_once "PDO.DB.class.php";

$db = new DB();

$data = $db->getPerson(1);
var_dump($data);
foreach($data as $row) {
    print_r($row);
}
echo "<hr />";

$data = $db->getPersonAlt(1);

foreach($data as $row) {
    print_r($row);
}
echo "<hr />";

$data = $db->getPersonAlt2(1);

foreach($data as $row) {
    print_r($row);
}
echo "<hr />";
?>