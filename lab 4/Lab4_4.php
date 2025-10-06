<?php
    require_once("PDO.DB.class.php");

    // Redirect if no id
    if (!isset($_GET['id'])) {
        header("Location: Lab4_3.php");
        exit();
    }

    $id = intval($_GET['id']);
    $db = new DB();

    // FETCH_ASSOC
    $phones = $db->getPhonesByPersonAssoc($id);//get phone as array

    echo "<h2>Phone Numbers</h2>";
    if (count($phones) > 0) {//check if phone number found
        echo "<p># " . count($phones) . " records found!</p>";
        echo "<table border='1'>";
        echo "<tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>";
        foreach($phones as $p) {//loop through record
            echo "<tr>";
            //Display from array
            echo "<td>".$p['PersonID']."</td>";
            echo "<td>".$p['PhoneType']."</td>";
            echo "<td>".$p['PhoneNum']."</td>";
            echo "<td>".$p['AreaCode']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No phone numbers found for this person.</p>";//message
    }

    // FETCH_CLASS
    $phones2 = $db->getPhonesByPersonClass($id);//get phones as PhoneNumeber objects

    echo "<h2>Phone Numbers</h2>";
    if (count($phones2) > 0) {
        echo "<p># " . count($phones2) . " records found!</p>";
        echo "<table border='1'>";
        echo "<tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>";
        foreach($phones2 as $p) {//loop
            echo "<tr>";
            //Display from object
            echo "<td>".$p->PersonID."</td>";
            echo "<td>".$p->PhoneType."</td>";
            echo "<td>".$p->PhoneNum."</td>";
            echo "<td>".$p->AreaCode."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No phone numbers found for this person.</p>";
    }

    echo "<p><a href='Lab4_3.php'>(Go back to Lab4_3.php)</a></p>";//back link
?>