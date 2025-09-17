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

    }//getAllPeopleAsTable

}//DB