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
            $gala = $row["anzeige_gala"];
            $zier = $row["anzeige_zier"];
            $werker = $row["werker_gewertet"];
            $imquiz = $row["im_quiz"];
      
        array_push($data,array("id"=>$id, "kategorie"=>$katName, "anzeige_gartenlandschaftsbau"=>$gala, "anzeige_ziergartenbau"=>$zier, "werker_gewertet"=>$werker, "im_quiz"=>$imquiz));
      }
      
      $result->free();
      genJson($data); //Verarbeitung zu json
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

  //////// INSERT
function createKategorie($connection, $kat_name, $bool_gala, $bool_zier, $werker, $imquiz) {
  $sqlStmt = "INSERT INTO p_kategorien (kat_name, anzeige_gala, anzeige_zier, werker_gewertet, im_quiz) 
                VALUES ('$kat_name', '$bool_gala','$bool_zier', '$werker', '$imquiz')";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
closeConnection($connection);
}

  //////// UPDATE
  function updateKategorie($connection, $id, $kat_name, $bool_gala, $bool_zier, $werker, $imquiz) {
    $sqlStmt = "UPDATE p_kategorien SET kat_name = '$kat_name', anzeige_gala = '$bool_gala', anzeige_zier = '$bool_zier', werker_gewertet = '$werker', im_quiz = '$imquiz'
                WHERE id = '$id'";
    if (!$connection->query($sqlStmt) && !$connection->query($sqlStmt2)) {
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