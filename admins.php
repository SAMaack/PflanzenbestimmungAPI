<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

function getAdmins ($connection) {
    $sqlStmt = "SELECT * FROM admins";
    $result =  mysqli_query($connection, $sqlStmt);

    $data = array();

    if ($result = $connection->query($sqlStmt)) {
        while ($row = $result->fetch_assoc()) {

            $id = $row["id"];
            $nutzer = $row["nutzername"];
            $berflag = $row["berflag"];
    
            array_push($data,array("id"=>$id, "nutzername"=>$nutzer, "berflag"=>$berflag));
        }
      
        $result->free();
        genJson($data);
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

//CREATE

function createAdmin($connection, $nutzername, $pw) {
  $sqlStmt = "INSERT INTO admins (nutzername, passwort) 
              VALUES ('$nutzername', '$pw')";

              //Error-Abfrage?
  $connection->query($sqlStmt);
  closeConnection($connection);
}

//UPDATE

function updateAdmin($connection, $uID, $nutzername, $pw) {
  $sqlStmt = "UPDATE admins
              SET nutzername = '$nutzername', passwort = '$pw'
              WHERE id = '$uID'";
              
              //Error-Abfrage?
  $connection->query($sqlStmt);
  closeConnection($connection);
}

//DELETE

?>