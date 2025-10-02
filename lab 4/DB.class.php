<?php

class DB {

    private $conn;

    function __construct(){
        $this->conn = new mysqli($_SERVER['DB_SERVER'],$_SERVER['DB_USER'],
                $_SERVER['DB_PASSWORD'],$_SERVER['DB']);
        if ($this->conn->connect_error) {
            echo "db connection failed: ".mysqli_connect_error();
            die();
        }
    }//constructor

    function getAllPeople(){

        $data = [];

        if ($stmt = $this->conn->prepare("SELECT * FROM people")){
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id,$last,$first,$nick);

            if($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    $data[] = [
                        'id' => $id,
                        'first' => $first,
                        'last' => $last,
                        'nick' => $nick
                    ];
                }
            }
        }

        return $data;

    }//getAllPeople

    function getAllPeopleAsTable() {
       
        $data = $this->getAllPeople();

        if (count($data) > 0) {
       
            $bigString = "<table border='1'>\n
            <tr><th>ID</th>
                <th>First</th>
                <th>Last</th>
                <th>Nick</th></tr>\n";

        foreach ($data as $row) {
            $bigString .= "<tr>
                            <td><a href='Lab4_2.php?id={$row['id']}'>{$row['id']}</a></td>
                            <td>{$row['first']}</td>
                            <td>{$row['last']}</td>
                            <td>{$row['nick']}</td>
                        </tr>\n";
        }

            $bigString .= "</table>\n";
            $bigString .= "<p># Records found: " . count($data) . "</p>";//add recrod count display
        } else {
            $bigString = "<h2>No people exist.</h2>\n";
        }

        return $bigString;

    }//getAllPeopleAsTable

    function insert($last,$first,$nick) {
        
        $query = "INSERT INTO people (LastName, FirstName, NickName) VALUES (?,?,?)";
        $insertID = -1;

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("sss",$last,$first,$nick);
            $stmt->execute();
            $stmt->store_result();
            $insertID = $stmt->insert_id;
        }

        return $insertID;
    
    }//insert

    function update($fields) {
        $query = "UPDATE people SET ";
        $items = [];
        $types = "";
        $updateID = 0;

        foreach ($fields as $k => $v) {
            switch($k){
                case "nick":
                    $query .= "NickName = ?, ";
                    $items[] = $v;
                    $types .= "s";
                    break;
                case "first":
                    $query .= "FirstName = ?, ";
                    $items[] = $v;
                    $types .= "s";
                    break;
                case "last":
                    $query .= "LastName = ?, ";
                    $items[] = $v;
                    $types .= "s";
                    break;
                case "id":
                    $updateID = intval($v);
                    break;
                default:
                    // ignore unknown fields
                    break;
            }
        }

        $query = rtrim($query, ", "); // remove trailing comma
        $query .= " WHERE PersonID = ?";
        $types .= "i";
        $items[] = $updateID;

        $numRows = 0;
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param($types, ...$items);
            $stmt->execute();
            $numRows = $stmt->affected_rows;
        }

        return $numRows;

    }//update

    function delete($id) {
        $query = "DELETE FROM people WHERE PersonID = ? ";
        $numRows = 0;
        if ($stmt = $this->conn->prepare($query)){
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->store_result();
            $numRows = $stmt->affected_rows;
        }

        return $numRows;

    }//delete

    public function getPhonesByPerson($id) {//add new method getphone for lab 4
        $data = [];

        if ($stmt = $this->conn->prepare("SELECT PersonID, PhoneType, AreaCode, PhoneNum FROM phonenumbers WHERE PersonID = ?")){
            $stmt->bind_param("i", $id);//bind id para as integer
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($personID,$phoneType,$areaCode,$phoneNum);

            if($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    $data[] = [
                        'PersonID' => $personID,
                        'PhoneType' => $phoneType,
                        'AreaCode' => $areaCode,
                        'PhoneNum' => $phoneNum
                    ];
                }
            }
        }

        return $data;
    }

}//DB