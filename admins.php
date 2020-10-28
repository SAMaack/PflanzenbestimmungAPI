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
            $vorname = $row["vorname"];
            $name = $row["name"];
            $berflag = $row["berflag"];
    
            array_push($data,array("id"=>$id, "nutzername"=>$nutzer, "vorname"=>$vorname, "name"=>$name, "berflag"=>$berflag));
        }
      
        $result->free();
        genJson($data);
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

//CREATE

function createAdmin($connection, $nutzername, $pw, $vorname, $name) {
  $sqlStmt = "INSERT INTO admins (nutzername, passwort, vorname, name) 
              VALUES ('$nutzername', '$pw', '$vorname', '$name')";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

//UPDATE

function updateAdmin($connection, $uID, $nutzername, $pw, $vorname, $name) {
  $sqlStmt = "UPDATE admins
              SET nutzername = '$nutzername', passwort = '$pw', vorname = '$vorname', name = '$name'
              WHERE id = '$uID'";
              
    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
  closeConnection($connection);
}

function updateAdminWithoutPassword($connection, $uID, $nutzername, $vorname, $name) {
  $sqlStmt = "UPDATE admins
              SET nutzername = '$nutzername', vorname = '$vorname', name = '$name'
              WHERE id = '$uID'";
              
    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
  closeConnection($connection);
}

//DELETE

function deleteAdmin($connection, $id_admin) {
  $sqlStmt = "UPDATE azubis 
              SET fk_ausbilder = 1 
              WHERE fk_ausbilder = $id_admin";                          

  $sqlStmt1 = "DELETE FROM admins 
               WHERE id = $id_admin";

  if (!$connection->query($sqlStmt) || !$connection->query($sqlStmt1)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);//*/

}

?>