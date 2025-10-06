<?php
    require_once("DB.class.php");

    // Redirect if no id
    if (!isset($_GET['id'])) {
        header("Location: Lab4_1.php");
        exit();
    }

    $id = intval($_GET['id']);
    $db = new DB();

    $phones = $db->getPhonesByPerson($id);

    echo "<h2>Phone Numbers</h2>";
    if (count($phones) > 0) {
        echo "<p># " . count($phones) . " records found!</p>";
        echo "<table border='1'>";
        echo "<tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>";
        foreach($phones as $p) {
            echo "<tr>";
            echo "<td>".$p['PersonID']."</td>";
            echo "<td>".$p['PhoneType']."</td>";
            echo "<td>".$p['PhoneNum']."</td>";
            echo "<td>".$p['AreaCode']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No phone numbers found for this person.</p>";
    }

    echo "<p><a href='Lab4_1.php'>(Go back to Lab4_1.php)</a></p>";
?>