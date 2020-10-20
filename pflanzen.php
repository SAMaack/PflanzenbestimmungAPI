<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 



////// ABFRAGE PFLANZEN + KATEGORIEN UND ANTWORTEN
function getPflanzen($connection) {
    $sqlStmt = "SELECT * FROM pflanze";
    $data = array();

    if ($result = $connection->query($sqlStmt)) {
      while ($row = $result->fetch_assoc()) {

        $id = $row["id"];
        $zier = $row["zierbau"];
        $gala = $row["galabau"];

        $sqlStmt2 = "SELECT pk.id as id_kat, pk.kat_name, pk.anzeige_zier, pk.anzeige_gala, pk.werker_gewertet, pk.im_quiz, pa.antwort FROM p_antworten pa
                    INNER JOIN p_kategorien pk
                    ON pk.id = pa.fk_kategorie
                    WHERE fk_pflanze = '$id'";

        $antworten = array();

        if($result2 = $connection->query($sqlStmt2)) {
          while ($row = $result2->fetch_assoc()) {

            $idkat = $row["id_kat"];
            $katname = $row["kat_name"];
            $antwort = $row["antwort"];
            $anzeige_zier = $row["anzeige_zier"];
            $anzeige_gala = $row["anzeige_gala"];
            $wertung = $row["werker_gewertet"];
            $imquiz = $row["im_quiz"];

            array_push($antworten, array("kategorie_id"=>$idkat, "antwort"=>$antwort, "kategorie_name"=>$katname, 
                                        "anzeige_zier"=>$anzeige_zier, "anzeige_gala"=>$anzeige_gala, "wertung_werker"=>$wertung, "im_quiz"=>$imquiz));
          }
        }

        array_push($data, array("id_pflanze"=> $id, "zierpflanzenbau"=>$zier, "gartenlandschaftsbau"=>$gala, "kategorien"=>$antworten));
      }
      genJson($data); //Verarbeitung zu json
    }
    else
    {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}
   

/*

*/
  /* args Inhalt
  array(1) { 
    [0]=> array(30) { 
      [0]=> int(0) 
      [1]=> int(1) 
      [2]=> int(1) 
      [3]=> string(11) "GattungTest" 
      [4]=> int(2) 
      [5]=> string(7)...

  */


////// ERSTELLUNG VON EINER PFLANZE UND DEREN ANTWORTEN
function createPflanze($connection, $args) {

  $sqlLock  = "LOCK TABLES pflanze WRITE";  //Table sperren, damit dir LAST_INSERT_ID nicht gleich überschrieben wird.
  $sqlUnlock = "UNLOCK TABLES";
  $sqlStartTransaction = "START TRANSACTION";
  $sqlCommit = "COMMIT";

  $sqlInsert = "INSERT INTO pflanze (zierbau, galabau) VALUES ('$args[0]', '$args[1]')";
  $sqlSelect = "SELECT LAST_INSERT_ID() AS id";

  //Sperrung von Tabelle und Start von Transaction
  if ($connection->query($sqlLock) && $connection->query($sqlStartTransaction)) {
    //Einfügen von neuer Pflanze und selektieren von dessen ID
    if ($connection->query($sqlInsert) && $result = $connection->query($sqlSelect)) {
      //Entsperren von Tabelle
      if (!$connection->query($sqlUnlock)) {
        echo mysqli_error($connection); //Error
      }

      //Auslesen und zuweisen von ID zu Variable
      if ($row = $result->fetch_assoc()) {
        $id = $row["id"];

        //Statement vorbereitung
        $sqlStmt2 = "INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES ";
        
        for ($i = 2; $i < count($args); $i = $i+2) {

          $i2 = $i + 1; 
          $sqlStmt2 .= "('$id', '$args[$i]', '$args[$i2]')";  //Statement erweiterung, solange Statements vorhanden

          if ($i < count($args) - 2) {
            $sqlStmt2 .= ",";
          }
        }

        //Ausführung von Statement 2, Einfügung von Antworten
        if ($connection->query($sqlStmt2)) {
          if(!$connection->query($sqlCommit)) {
            echo mysqli_error($connection);
          }
        }
        else {
          echo mysqli_error($connection); //Error
        }
      }
    }
    else {
      echo mysqli_error($connection); //Error   
    }
  }
  else {
    echo mysqli_error($connection); //Error
  }

  closeConnection($connection); //Verbindung schließen
} // FUNKTIONS ENDE


////// 	AKTUALISIEREN VON EINER PFLANZE UND DEREN ANTWORTEN
function updatePflanze($connection, $args) {

  $sqlLock  = "LOCK TABLES p_antworten WRITE, pflanze WRITE";  //Table sperren, damit dir LAST_INSERT_ID nicht gleich überschrieben wird.
  $sqlUnlock = "UNLOCK TABLES";
  $sqlStartTransaction = "START TRANSACTION";
  $sqlCommit = "COMMIT";

  $id = $args[0];

  $sqlUpdate = "UPDATE pflanze SET galabau = '$args[1]', zierbau = '$args[2]' WHERE id = '$id'";
  $sqlDelete = "DELETE FROM p_antworten WHERE fk_pflanze = $id";

  //Sperrung von Tabelle und Start von Transaction
  if ($connection->query($sqlLock) && $connection->query($sqlStartTransaction)) {
    //Atkualisieren von der Pflanze an sich
    if ($connection->query($sqlUpdate)) {
      //Entfernen alter Einträge
      if ($connection->query($sqlDelete)) {
        //Statement vorbereitung
        $sqlStmt2 = "INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES ";
        
        for ($i = 3; $i < count($args); $i = $i+2) {

          $i2 = $i + 1; 
          $sqlStmt2 .= "('$id', '$args[$i]', '$args[$i2]')";  //Statement erweiterung, solange Statements vorhanden

          if ($i < count($args) - 2) {
            $sqlStmt2 .= ",";
          }
        }

        //Ausführung von Statement 2, Einfügung von Antworten
        if ($connection->query($sqlStmt2)) {
          //Entsperren von Table
          if ($connection->query($sqlUnlock)) {
            //Commit
            if(!$connection->query($sqlCommit)) {
              echo mysqli_error($connection); //Error
            }
          }
          else {
            echo mysqli_error($connection); //Error
          }
        }
        else {
            echo mysqli_error($connection); //Error   
        }
      }
      else {
        echo mysqli_error($connection); //Error   
      }
    }
    else {
      echo mysqli_error($connection); //Error   
    }
  }
  else {
    echo mysqli_error($connection); //Error
  }

  closeConnection($connection); //Verbindung schließen
} // FUNKTIONS ENDE

/////// PFLANZE LÖSCHEN

function deletePflanze($connection, $IDp) {
  $sqlStmt = "DELETE FROM pflanze
              WHERE id = '$IDp'";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

/////// ABFRAGE PFLANZEN BILDER
function getPBilder($connection, $id_pflanze) {
  $sqlStmt = "SELECT * FROM p_bilder WHERE fk_pflanze = '$id_pflanze'";

  $data = array();

  if ($result = $connection->query($sqlStmt)) {
    while ($row = $result->fetch_assoc()) {
      $id = $row["id"];
      $bild = $row["bild"];
      
      array_push($data, array("id_bild"=>$id, "bild"=>$bild));
    }

    genJson($data);
  }
  else {
    echo mysqli_error($connection);
  }
  $result->free();
  closeConnection($connection);
}


//////// INSERT
function createPBild($connection, $id_pflanze, $bild) {
  $sqlStmt = "INSERT INTO p_bilder (fk_pflanze, bild) 
              VALUES ('$id_pflanze', '$bild')";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

//////// DELETE
function deletePBild($connection, $id) {
  $sqlStmt = "DELETE FROM p_bilder
              WHERE id = '$id'";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

?>