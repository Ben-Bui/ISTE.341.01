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

    }//getperson

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

    }//getperson

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

    }//getperson alt2

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

    }//insert

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
    }//getAllObject

    // NEW METHODS ADDED FOR LAB 4
    function getAllPeople() {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM people");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $pe) {
            echo $pe->getMessage();
        }
        return $data;
    }

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

    public function getPhonesByPersonAssoc($id) {
        try {
            $stmt = $this->dbh->prepare("SELECT PersonID, PhoneType, AreaCode, PhoneNum FROM phonenumbers WHERE PersonID = ?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $pe) {
            echo $pe->getMessage();
            return [];
        }
    }

    public function getPhonesByPersonClass($id) {
        try {
            $stmt = $this->dbh->prepare("SELECT PersonID, PhoneType, AreaCode, PhoneNum FROM phonenumbers WHERE PersonID = ?");
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_CLASS, "PhoneNumber");
        } catch(PDOException $pe) {
            echo $pe->getMessage();
            return [];
        }
    }

}//DB