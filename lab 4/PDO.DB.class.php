<?php
class DB {

    private $dbh;

    function __construct(){
        try {
            $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']};dbname={$_SERVER['DB']}",
                $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $pe){
            echo $pe->getMessage();
            die("Bad Database Connection");
        }
    }

    function getAllPeople() {
        $data = [];

        try {
            $stmt = $this->dbh->prepare("SELECT * FROM people");
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data[] = $row;
            }

        } catch(PDOException $pe){
            echo $pe->getMessage();
        }

        return $data;
    }

    function getAllPeopleAsTable() {//to create html table
        $data = $this->getAllPeople();

        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
            <tr><th>ID</th>
                <th>First</th>
                <th>Last</th>
                <th>Nick</th></tr>\n";

            foreach ($data as $row) {
                $bigString .= "<tr>
                                <td><a href='Lab4_4.php?id={$row['PersonID']}'>{$row['PersonID']}</a></td>
                                <td>{$row['FirstName']}</td>
                                <td>{$row['LastName']}</td>
                                <td>{$row['NickName']}</td>
                            </tr>\n";
            }
            $bigString .= "</table>\n";
            $bigString .= "<p># Records found: " . count($data) . "</p>";
        } else {
            $bigString = "<h2>No people exist.</h2>\n";
        }
        return $bigString;
    }

    public function getPhonesByPersonAssoc($id) {//get phone as array
        $data = [];

        try {
            $stmt = $this->dbh->prepare("SELECT PersonID, PhoneType, AreaCode, PhoneNum FROM phonenumbers WHERE PersonID = :id");
            $stmt->execute(["id"=>$id]);//run query with id v
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//loop, get each row as aray
                $data[] = $row;//add phone to data array
            }

        } catch(PDOException $pe){
            echo $pe->getMessage();//show if something wrong
        }

        return $data;
    }

    public function getPhonesByPersonClass($id) {//get phone as object
        $data = [];

        try {
            include_once "PhoneNumber.class.php"; //load PhoneNumber class definition
            $stmt = $this->dbh->prepare("SELECT PersonID, PhoneType, AreaCode, PhoneNum FROM phonenumbers WHERE PersonID = :id");//prepare sql with id parameter
            $stmt->execute(["id"=>$id]);//run query with id
            $stmt->setFetchMode(PDO::FETCH_CLASS,"PhoneNumber");//convert each row into object
            
            while ($phone = $stmt->fetch()){//loop, getting each row as PhoneNumber object
                $data[] = $phone;//add phone object to array
            }

        } catch(PDOException $pe){
            echo $pe->getMessage();
        }

        return $data;
    }

    // Keep original class methods
    function getPerson($id) {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM people WHERE PersonID = :id");
            $stmt->execute(["id"=>$id]);
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();
        }
        return $data;
    }

    function getPersonAlt($id){
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM people WHERE PersonID = :id");
            $stmt->bindParam(":id",$id,PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()){
                $data[] = $row;
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();
        }
        return $data;
    }

    function getPersonAlt2($id){
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM people WHERE PersonID = :id");
            $stmt->execute(["id"=>$id]);
            $data = $stmt->fetchAll();
        } catch(PDOException $pe){
            echo $pe->getMessage();
        }
        return $data;
    }

    function insert($last,$first,$nick){
        try {
            $stmt = $this->dbh->prepare("INSERT INTO people(LastName, FirstName, NickName)
                VALUES (:lastName, :firstName, :nickName)");
            $stmt->execute(["lastName"=>$last, "firstName"=>$first, "nickName"=>$nick]);
            return $this->dbh->lastInsertId();
        } catch(PDOException $e){
            echo $e->getMessage();
            return -1;
        }
    }

    function getAllObjects() {
        $data = [];
        include "Person.class.php";
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM people ");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Person");
            while ($person = $stmt->fetch()){
                $data[] = $person;
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();
        }
        return $data;
    }

}//DB