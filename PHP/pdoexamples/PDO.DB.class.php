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
}//DB