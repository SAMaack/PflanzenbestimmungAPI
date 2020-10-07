<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 


function getAbgefragt($connection, $id_azubi) {
    $sqlStmt = "SELECT * FROM abgefragt WHERE fk_azubi = '$id_azubi'";
    $result =  mysqli_query($connection, $sqlStmt);

    $data = array();

    if ($result = $connection->query($sqlStmt)) {
      while ($row = $result->fetch_assoc()) {

            $id_pflanze = $row['fk_azubi'];
            $counter = $row['counter_korrekt'];
            $gelernt = $row['gelernt'];

        array_push($data,array("IDp"=>$id_pflanze, "Counter"=>$counter, "Bool_Gelernt"=>$gelernt));
      }
      
      $result->free();
      genJson($data); //Verarbeitung zu json
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

  //////// INSERT
function createAbgefragt($connection, $id_azubi, $id_pflanze, $counter, $gelernt) {
    $sqlStmt = "INSERT INTO abgefragt (fk_azubi, fk_pflanze, counter_korrekt, gelernt) 
                VALUES ('$id_azubi', '$id_pflanze', '$counter', '$gelernt')";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}


  function updateAbgefragt($connection, $id_azubi, $id_pflanze, $counter, $gelernt) {
    $sqlStmt = "UPDATE abgefragt SET counter_korrekt = '$counter, gelernt = '$gelernt'
                WHERE fk_azubi = '$id_azubi' AND fk_pflanze = 'id_pflanze'";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}
?>