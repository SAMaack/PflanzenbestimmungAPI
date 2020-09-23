<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 


  ////////////////////// STATISTIK
  
  //////// GET
  function getStatistik ($connection, $id_azubi) {
    $sqlStmt = "SELECT * 
                FROM statistik
                WHERE fk_azubi = '$id_azubi'"; 

    $data = array();
    
    if ($result = $connection->query($sqlStmt)) {
      $stat = array();

        while ($row = $result->fetch_assoc()) {
          $pflanze = array();

          $id_statistik = $row["id"];
          $timestamp = $row["log"];
          $fehlerq = $row["fehlerquote"];
          $zeit = $row["quizzeit"];
          $id_b_pflanze = $row["fk_beste_pflanze"];

          array_push($data, array("id_statistik"=>$id_statistik, "erstellt"=>$timestamp, "fehlerquote"=>$fehlerq, "zeit"=>$zeit, "id_beste_pflanze"=>$id_b_pflanze));

          $sqlStmt = "SELECT fk_pflanze
                      FROM stat_einzel
                      WHERE fk_statistik = '$id_statistik'";

          if ($result2 = $connection->query($sqlStmt)) {
            while ($row = $result2->fetch_assoc()) {
              $id_pflanze = $row["fk_pflanze"];

              $sqlStmt = "SELECT se.fk_pflanze, pk.kat_name, sed.eingabe, sed.korrekt FROM stat_einzel se
              JOIN stat_einzel_detail sed ON se.fk_statistik = sed.fk_statistik 
              JOIN p_kategorien pk ON sed.fk_kategorie = pk.id
              WHERE se.fk_statistik = '$id_statistik' AND sed.fk_pflanze = '$id_pflanze'";

              if ($result3 = $connection->query($sqlStmt)) {
                while ($row = $result3->fetch_assoc()) {
                  $kategorie = $row["kat_name"];
                  $eingabe = $row["eingabe"];
                  $korrekt = $row["korrekt"];
                }

                array_push($data, array("id_pflanze"=>$id_pflanze, "kategorie"=>$kategorie, "eingabe"=>$eingabe, "korrekt"=>$korrekt));

              }//END IF
            }//END WHILE
          }//END IF
        }//END WHILE

        $result->free();
        $result2->free();
        $result3->free();
        genJson($data);
      }
      else{
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
  ///
  ///// INSERT - Einzel
  function createStatEinzel($connection, $id_stat, $id_pflanze, $id_kategorie) {
    $sqlStmt = "INSERT into stat_einzel (fk_statistik, fk_pflanze, benoetigte_zeit) VALUES ('$id_stat', '$id_pflanze')";
                
    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
  }

  //////// INSERT - Einzel - Details
  function createStatEinzelDetail($connection, $id_stat, $id_pflanze, $id_kategorie, $eingabe) {
    $sqlStmt = "INSERT INTO stat_einzel_detail (fk_statistik, fk_pflanze, fk_kategorie, eingabe, korrekt
    VALUES('$id_stat', '$id_pflanze', '$id_kategorie', '$eingabe', (SELECT antwort from p_antworten WHERE fk_kategorie = '$id_kategorie' AND $fk_pflanze = '$id_pflanze'))";
                
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
?>
