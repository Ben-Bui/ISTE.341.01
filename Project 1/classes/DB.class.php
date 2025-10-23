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

    function getUserByUsername($username) {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM user_details WHERE Username = :username");
            $stmt->execute(["username"=>$username]);//run query with username parameter
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);//get single row as associative array
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function getAllUsers() {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM user_details");
            $stmt->execute();//run query to get all users
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//loop through each row
                $data[] = $row;//add user to data array
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function insertUser($username, $password, $roleId, $projectId, $name) {
        $insertID = -1;
        $hashedPassword = hash('sha256', $password);//hash password using sha256
        try {
            $stmt = $this->dbh->prepare("INSERT INTO user_details (Username, Password, RoleID, ProjectId, Name) VALUES (:username, :password, :roleId, :projectId, :name)");
            $stmt->execute([
                "username"=>$username,
                "password"=>$hashedPassword,
                "roleId"=>$roleId,
                "projectId"=>$projectId,
                "name"=>$name
            ]);//run insert query with all parameters
            $insertID = $this->dbh->lastInsertId();//get the new user ID
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $insertID;
    }

    function deleteUser($id) {
        $numRows = 0;
        try {
            // Remove user from bugs assignments first
            $stmt1 = $this->dbh->prepare("UPDATE bugs SET assignedToId = NULL WHERE assignedToId = :id");
            $stmt1->execute(["id"=>$id]);//clear user from bug assignments
            
            // Delete user
            $stmt2 = $this->dbh->prepare("DELETE FROM user_details WHERE id = :id");
            $stmt2->execute(["id"=>$id]);//delete user from database
            $numRows = $stmt2->rowCount();//get number of rows affected
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $numRows;
    }

    function getAllBugs($filters = []) {
        $data = [];
        $query = "SELECT b.*, p.Project as project_name, u1.Name as owner_name, u2.Name as assigned_name 
                FROM bugs b 
                LEFT JOIN project p ON b.projectId = p.Id 
                LEFT JOIN user_details u1 ON b.ownerId = u1.Id 
                LEFT JOIN user_details u2 ON b.assignedToId = u2.Id 
                WHERE 1=1";//start with always true condition
        
        $params = [];//array to store parameters
        
        if (isset($filters['project']) && $filters['project'] != 'all') {
            $query .= " AND b.projectId = :project";//add project filter
            $params["project"] = $filters['project'];//store project parameter
        }
        
        if (isset($filters['status']) && $filters['status'] == 'open') {
            $query .= " AND b.statusId != 3";//show only open bugs (not closed)
        }
        
        if (isset($filters['status']) && $filters['status'] == 'overdue') {
            $query .= " AND b.targetDate < NOW() AND b.statusId != 3 AND b.targetDate IS NOT NULL";//show overdue, not closed, and has target date
        }

        if (isset($filters['status']) && $filters['status'] == 'unassigned') {
            $query .= " AND b.assignedToId IS NULL";//show unassigned bugs
        }
        
        $query .= " ORDER BY b.dateRaised DESC";//sort by newest first
        
        try {
            $stmt = $this->dbh->prepare($query);
            $stmt->execute($params);//run query with parameters
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//get each row as associative array
                $data[] = $row;//add bug to data array
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function getBugById($id) {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM bugs WHERE id = :id");
            $stmt->execute(["id"=>$id]);//run query with id parameter
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);//get single row as associative array
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function insertBug($bugData) {
        $insertID = -1;
        try {
            $stmt = $this->dbh->prepare("INSERT INTO bugs (projectId, ownerId, assignedToId, statusId, priorityId, summary, description, targetDate) VALUES (:projectId, :ownerId, :assignedToId, :statusId, :priorityId, :summary, :description, :targetDate)");
            $stmt->execute([
                "projectId"=>$bugData['projectId'],
                "ownerId"=>$bugData['ownerId'],
                "assignedToId"=>$bugData['assignedToId'],
                "statusId"=>$bugData['statusId'],
                "priorityId"=>$bugData['priorityId'],
                "summary"=>$bugData['summary'],
                "description"=>$bugData['description'],
                "targetDate"=>$bugData['targetDate']
            ]);//run insert query with all bug data
            $insertID = $this->dbh->lastInsertId();//get the new bug ID
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $insertID;
    }

    function updateBug($id, $bugData) {
        $numRows = 0;
        try {
            $stmt = $this->dbh->prepare("UPDATE bugs SET assignedToId=:assignedToId, statusId=:statusId, priorityId=:priorityId, summary=:summary, description=:description, fixDescription=:fixDescription, targetDate=:targetDate, dateClosed=:dateClosed WHERE id=:id");
            $stmt->execute([
                "assignedToId"=>$bugData['assignedToId'],
                "statusId"=>$bugData['statusId'],
                "priorityId"=>$bugData['priorityId'],
                "summary"=>$bugData['summary'],
                "description"=>$bugData['description'],
                "fixDescription"=>$bugData['fixDescription'],
                "targetDate"=>$bugData['targetDate'],
                "dateClosed"=>$bugData['dateClosed'],
                "id"=>$id
            ]);//run update query with all parameters
            $numRows = $stmt->rowCount();//get number of rows affected
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $numRows;
    }

    function getAllProjects() {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM project");
            $stmt->execute();//run query to get all projects
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//loop through each row
                $data[] = $row;//add project to data array
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function insertProject($name) {
        $insertID = -1;
        try {
            $stmt = $this->dbh->prepare("INSERT INTO project (Project) VALUES (:name)");
            $stmt->execute(["name"=>$name]);//run insert query with project name
            $insertID = $this->dbh->lastInsertId();//get the new project ID
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $insertID;
    }

    function updateProject($id, $name) {
        $numRows = 0;
        try {
            $stmt = $this->dbh->prepare("UPDATE project SET Project = :name WHERE Id = :id");
            $stmt->execute(["name"=>$name, "id"=>$id]);//run update query
            $numRows = $stmt->rowCount();//get number of rows affected
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $numRows;
    }

    function getRoles() {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM role");
            $stmt->execute();//run query to get all roles
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//loop through each row
                $data[] = $row;//add role to data array
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function getStatuses() {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM bug_status");
            $stmt->execute();//run query to get all statuses
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//loop through each row
                $data[] = $row;//add status to data array
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function getPriorities() {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM priority");
            $stmt->execute();//run query to get all priorities
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//loop through each row
                $data[] = $row;//add priority to data array
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function getUsersByProject($projectId) {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM user_details WHERE ProjectId = :projectId");
            $stmt->execute(["projectId"=>$projectId]);//run query with project ID
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//loop through each row
                $data[] = $row;//add user to data array
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function getAllUsersForAssignment() {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM user_details WHERE RoleID != 1");//exclude admins
            $stmt->execute();//run query to get assignable users
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){//loop through each row
                $data[] = $row;//add user to data array
            }
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

    function getUserProject($userId) {
        $data = [];
        try {
            $stmt = $this->dbh->prepare("SELECT ProjectId FROM user_details WHERE id = :userId");
            $stmt->execute(["userId"=>$userId]);//run query with user ID
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);//get single row as associative array
        } catch(PDOException $pe){
            echo $pe->getMessage();//show error if something wrong
        }
        return $data;
    }

}//DB
?>