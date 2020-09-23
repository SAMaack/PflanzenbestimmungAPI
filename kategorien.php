<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

function getKategorien($connection) {
    $sqlStmt = "SELECT * FROM p_kategorien ORDER BY id ASC";
    $result =  mysqli_query($connection, $sqlStmt);

    $data = array();

    if ($result = $connection->query($sqlStmt)) {
      while ($row = $result->fetch_assoc()) {

            $id = $row["id"];
            $katName = $row["kat_name"];
            $abfrage = $row["abfrage"];

        array_push($data,array("id"=>$id, "kategorie"=>$katName, "abfrage"=>$abfrage));
      }
      
      $result->free();
      genJson($data); //Verarbeitung zu json
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

  //////// INSERT
function createKategorie($connection, $kat_name) {
    $sqlStmt = "INSERT INTO p_kategorien (kat_name) 
                VALUES ('$kat_name')";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

  //////// UPDATE - TODO
  function updateKategorie($connection, $id, $kat_name) {
    $sqlStmt = "UPDATE p_kategorien SET kat_name = '$kat_name'
                WHERE id = 'id'";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

  //////// DELETE
function deleteKategorie($connection, $id) {
  $sqlStmt = "DELETE FROM p_kategorien
              WHERE id = '$id'";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

?>