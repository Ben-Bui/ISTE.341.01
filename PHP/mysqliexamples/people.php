<?php
    require_once("DB.class.php");

    $db = new DB();
    echo $db->getAllPeopleAsTable();

    $id = $db->insert("Talor", "James", "TJ" );

    if($id > 0) {
        echo "<p>You inserted 1 row with an id of $id</p>";
    } else {
        echo "<p>Failed to insert row</p>";
    }

    $num = $db->update(['id'=>4, 'nick'=>"Jay"]);
    echo "<p>You update $num rows</p>";

    $num = $db->delete(4);
    echo "<p>You deleted $num rows</p>"
?>