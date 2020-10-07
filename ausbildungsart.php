<?php

$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

function getAusbildungsart($connection) {
    $sqlStmt = "SELECT * FROM ausbildungsart";
    $result =  mysqli_query($connection, $sqlStmt);

    $data = array();

    if ($result = $connection->query($sqlStmt)) {
      while ($row = $result->fetch_assoc()) {

            $id = $row["id"];
            $FName = $row["name"];

        array_push($data,array("id"=>$id, "ausbildungsart"=>$FName));
      }
      
      $result->free();
      genJson($data); //Verarbeitung zu json
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}


?>