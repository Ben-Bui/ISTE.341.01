<?php
    require_once("PDO.DB.class.php");

    // Redirect if no id
    if (!isset($_GET['id'])) {
        header("Location: Lab4_3.php");
        exit();
    }

    $id = intval($_GET['id']);
    $db = new DB();

    // PhoneNumber class definition
    class PhoneNumber {
        public $PersonID;
        public $PhoneType;
        public $AreaCode;
        public $PhoneNum;
    }

    // 1) Fetch phones with FETCH_ASSOC
    $phones = $db->getPhonesByPersonAssoc($id);

    echo "<h2>Phone Numbers (FETCH_ASSOC)</h2>";
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

    // 2) Fetch phones with FETCH_CLASS
    $phones2 = $db->getPhonesByPersonClass($id);

    echo "<h2>Phone Numbers (FETCH_CLASS)</h2>";
    if (count($phones2) > 0) {
        echo "<p># " . count($phones2) . " records found!</p>";
        echo "<table border='1'>";
        echo "<tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>";
        foreach($phones2 as $p) {
            echo "<tr>";
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

    echo "<p><a href='Lab4_3.php'>(Go back to Lab4_3.php)</a></p>";
?>