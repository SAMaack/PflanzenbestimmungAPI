<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 


  ////////////////////// STATISTIK
  
  //////// GET
  function getStatList ($connection, $id_azubi) {
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
          }//END WHILE
  
          $result->free();

          genJson($data);
        }
        else{
          echo mysqli_error($connection);
        }
      closeConnection($connection);
  }

  
function getStatistik ($connection, $id_stat) {
    $sqlStmt = "SELECT * 
                FROM statistik
                WHERE id = '$id_stat'"; 

    $data = array();
    
// ABFRAGE STATISTIK
if ($result = $connection->query($sqlStmt)) {
  $stat = array();
    
    while ($row = $result->fetch_assoc()) {
      $pflanze = array();

      // ERGEBNIS SPEICHERN
      $id_statistik = $row["id"];
      $timestamp = $row["log"];
      $fehlerq = $row["fehlerquote"];
      $zeit = $row["quizzeit"];
      $id_b_pflanze = $row["fk_beste_pflanze"];

      strtotime(date('Y-m-d H:i:s') . ' + 7 hours'); // HIER MUSS IRGENDWO EINE ZEIT KONVERTIERUNG EINGEFÜGT WERDEN. DB Abfrage der Timezone?

      // STATEMENT VORBEREITUNG
      $sqlStmt = "SELECT fk_pflanze
                  FROM stat_einzel
                  WHERE fk_statistik = '$id_statistik'";

      // ABFRAGE PFLANZE
      if ($result2 = $connection->query($sqlStmt)) {
        while ($row = $result2->fetch_assoc()) {
          // AKTUELLE PFLANZE
          $id_pflanze = $row["fk_pflanze"];

          // STATEMENT VORBEREITUNG
          $sqlStmt = "SELECT pk.kat_name, se.eingabe, se.korrekt
                      FROM stat_einzel_detail se
                      INNER JOIN p_kategorien pk ON se.fk_kategorie = pk.id
                      WHERE se.fk_statistik = '$id_stat' AND se.fk_pflanze = '$id_pflanze' ORDER 
                      BY fk_kategorie";

          $kat = array();
          
          //ABFRAGE VON AKTEGORIEN UND ANTWORTEN PRO PFLANZE
          if ($result3 = $connection->query($sqlStmt)) {
            while ($row = $result3->fetch_assoc()) {
              
              $kategorie = $row["kat_name"];
              $eingabe = $row["eingabe"];
              $korrekt = $row["korrekt"];
              array_push($kat, array("kategorie"=>$kategorie, "eingabe"=>$eingabe, "korrekt"=>$korrekt));
            }

            array_push($pflanze, array("id_pflanze"=>$id_pflanze, "antworten"=>$kat));

          }//END IF

          $result3->free(); // RESSOURCEN FREIGABE
        }//END WHILE

        $result2->free(); // RESSOURCEN FREIGABE
      }//END IF
      
      array_push($data, array("id_statistik"=>$id_statistik, "erstellt"=>$timestamp, "fehlerquote"=>$fehlerq, "zeit"=>$zeit, "id_beste_pflanze"=>$id_b_pflanze, "pflanzen"=>$pflanze));

    }//END WHILE

    $result->free(); // RESSOURCEN FREIGABE
    
    //  AUSGABE ALS JSON
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

  //////// INSERT - Einzel
  function createStatEinzelDetail($connection, $id_stat, $id_pflanze, $id_kategorie, $eingabe) {

    $sqlStmt = "SELECT * FROM stat_einzel WHERE fk_pflanze = '$id_pflanze' AND fk_statistik = '$id_stat'";

    if ($result =  $connection->query($sqlStmt)) { //Ob Verbindungseintrag für Pflanze schon besteht.
      if(mysqli_num_rows($result) < 1) {
        // Wenn nein, dann einfügen.
        $sqlStmt = "INSERT into stat_einzel (fk_statistik, fk_pflanze) VALUES ('$id_stat', '$id_pflanze')"; 
                        
      }
    }

    // Einfügen von Antwort pro Kategorie pro Pflanze
    $sqlStmt2 = "INSERT INTO stat_einzel_detail (fk_statistik, fk_pflanze, fk_kategorie, eingabe, korrekt)
                 VALUES('$id_stat', '$id_pflanze', '$id_kategorie', '$eingabe', (SELECT antwort from p_antworten 
                 WHERE fk_kategorie = '$id_kategorie' AND fk_pflanze = '$id_pflanze'))";
   
   if (mysqli_num_rows($result) < 1) {
      if (!$connection->query($sqlStmt)|| !$connection->query($sqlStmt2)) {
      echo mysqli_error($connection);
      }
   }
   else {
    if (!$connection->query($sqlStmt2)) {
      echo mysqli_error($connection);
      }
   }  
    $result->free();

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
