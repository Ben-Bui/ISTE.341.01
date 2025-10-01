<?php
    require_once("PDO.DB.class.php");

    $db = new DB();
    echo $db->getAllPeopleAsTable();
?>