<?php
    require_once("DB.class.php");//load databast
    $db = new DB();
    echo $db->getAllPeopleAsTable();
?>