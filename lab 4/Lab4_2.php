<?php
    require_once("DB.class.php");

    if (!isset($_GET['id'])) {
        header("Location: Lab4_1.php");
        exit;
    }

    $personID = $_GET['id'];
    $db = new DB();

    // Get phone numbers for the selected person using mysqli parameterized queries
    $data = [];
    if ($stmt = $db->conn->prepare("SELECT * FROM phonenumbers WHERE PersonID = ?")) {
        $stmt->bind_param("i", $personID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo "<h2>Phone Numbers for Person ID: $personID</h2>";
    
    if (count($data) > 0) {
        echo "<p># " . count($data) . " records found!</p>";
        echo "<table border='1'>";
        echo "<tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>";
        
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>{$row['PersonID']}</td>";
            echo "<td>{$row['PhoneType']}</td>";
            echo "<td>{$row['PhoneNum']}</td>";
            echo "<td>{$row['AreaCode']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No phone numbers found for this person.</p>";
    }
    
    echo "<p><a href='Lab4_1.php'>Go back to Lab4_1.php</a></p>";
?>