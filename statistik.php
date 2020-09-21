<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 


  ////////////////////// STATISTIK
  
  //////// GET
  function getStatistik ($connection, $id_azubi) {
    $sqlStmt = "SELECT * FROM statistik
                WHERE fk_azubi = '$id_azubi'";
    $result =  mysqli_query($connection, $sqlStmt);
    
    $data = array();
    
    if ($result = $connection->query($sqlStmt)) {
        while ($row = $result->fetch_assoc()) {
    
            $id = $row["id"];
            $timestamp = $row["log"];
            $fehlerq = $row["fehlerquote"];
            $avgz = $row["avgzeit"];
             
            array_push($data, array("id"=>$id, "log"=>$timestamp, "fehlerquote"=>$fehlerq, "avgzeit"=>$avgz));
          }
    
      genJson($data);
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
  }

  //////// INSERT
  function createStatistik($connection, $id_azubi, $fehler, $zeit, $id_pflanze) {
      $sqlStmt = "INSERT INTO statistik (fk_azubi, fehlerquote, quizzeit, fk_beste_pflanze) 
                  VALUES ('$id_azubi', '$fehler', '$zeit', '$id_pflanze')";

      if (!$connection->query($sqlStmt)) {
        echo mysqli_error($connection);
      }
      closeConnection($connection);
  }

  //////// UPDATE
  function updateStatistik($connection, $id_stat, $fehler, $zeit, $id_pflanze) {
    $sqlStmt = "UPDATE statistik 
                SET fehlerquote = '$fehler', quizzeit = '$zeit', fk_beste_pflanze = '$id_pflanze'
                WHERE id = 'id_stat'";
  
    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

  ////////////////////// STATISTIK - DETAILS
  function getStatDetails ($connection, $id_statistik) {
      $sqlStmt = "SELECT * FROM statistik_details WHERE fk_statistik = '$id_statistik'";
      $result =  mysqli_query($connection, $sqlStmt);
      
      $data = array();
      
      if ($result = $connection->query($sqlStmt)) {
          while ($row = $result->fetch_assoc()) {
      
            // Daten werden der Reihe nach abgerufen und an Variablen gebunden.       
            $id_pflanze = $row["fk_pflanze"];// -> "id_pflanze" wird gefühllt mit daten aus "fk_pflanze" in der aktuellen reihe"
            $logtime = $row["log"];        
            $bzeit= $row["benoetigte_zeit"];
            $eingabe  = $row["eingabe"];
            $korrekt = $row["korrekt"];

            // Array wird mit Daten gefüllt 
            array_push($data, array("id_pflanze" =>$id_pflanze, 
                                    "erstellt" =>$logtime, 
                                    "benoetigte zeit "=>$bzeit,
                                    "eingabe" => $eingabe, 
                                    "korrekt"=>$korrekt));

            
          }
      
        genJson($data);   // Übergabe zur Json konvertierung
      }else{
        echo mysqli_error($connection);
      }
      closeConnection($connection);
  }

  //////// INSERT
  function createStatDetails($connection, $id_stat, $id_pflanze, $id_kategorie, $zeit, $eingabe) {
    $sqlStmt = "INSERT into statistik_details (fk_statistik, fk_pflanze, fk_kategorie
                                               benoetigte_zeit, eingabe, korrekt)

                VALUES ('$id_stat', '$id_pflanze', '$id_kategorie', '$zeit', '$eingabe', (SELECT from p_antworten WHERE fk_kategorie = '$id_kategorie' AND $fk_pflanze = '$id_pflanze'))";
  
    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

?>