<?php
    require_once("PDO.DB.class.php");

    $db = new DB();
    
    // Create a method similar to getAllPeopleAsTable() but for PDO
    $data = $db->getAllPeople();
    
    if (count($data) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>First</th><th>Last</th><th>NickName</th></tr>";
        
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td><a href='Lab4_4.php?id={$row['PersonID']}'>{$row['PersonID']}</a></td>";
            echo "<td>{$row['FirstName']}</td>";
            echo "<td>{$row['LastName']}</td>";
            echo "<td>{$row['NickName']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<p># Records found: " . count($data) . "</p>";
    } else {
        echo "<h2>No people exist.</h2>";
    }
?>