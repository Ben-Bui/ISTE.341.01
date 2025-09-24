<?php
    require_once("PDO.DB.class.php");

    if (!isset($_GET['id'])) {
        header("Location: Lab4_3.php");
        exit;
    }

    $personID = $_GET['id'];
    $db = new DB();

    // First table using PDO::FETCH_ASSOC
    echo "<h2>Phone Numbers for Person ID: $personID (FETCH_ASSOC)</h2>";
    
    $data = [];
    try {
        $stmt = $db->dbh->prepare("SELECT * FROM phonenumbers WHERE PersonID = :id");
        $stmt->execute(["id" => $personID]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $pe) {
        echo $pe->getMessage();
    }

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

    // Second table using PDO::FETCH_CLASS
    echo "<h2>Phone Numbers for Person ID: $personID (FETCH_CLASS)</h2>";
    
    // Create PhoneNumber class
    class PhoneNumber {
        public $PersonID;
        public $PhoneType;
        public $PhoneNum;
        public $AreaCode;
    }

    $data2 = [];
    try {
        $stmt = $db->dbh->prepare("SELECT * FROM phonenumbers WHERE PersonID = :id");
        $stmt->execute(["id" => $personID]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "PhoneNumber");
        
        while ($phone = $stmt->fetch()) {
            $data2[] = $phone;
        }
    } catch(PDOException $pe) {
        echo $pe->getMessage();
    }

    if (count($data2) > 0) {
        echo "<p># " . count($data2) . " records found!</p>";
        echo "<table border='1'>";
        echo "<tr><th>Person ID</th><th>Phone Type</th><th>Phone #</th><th>Area Code</th></tr>";
        
        foreach ($data2 as $phone) {
            echo "<tr>";
            echo "<td>{$phone->PersonID}</td>";
            echo "<td>{$phone->PhoneType}</td>";
            echo "<td>{$phone->PhoneNum}</td>";
            echo "<td>{$phone->AreaCode}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No phone numbers found for this person.</p>";
    }
    
    echo "<p><a href='Lab4_3.php'>Go back to Lab4_3.php</a></p>";
?>