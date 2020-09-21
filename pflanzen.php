<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 


//ÜBERABEITUNG ZU AUSGABE VON ALLEN DATEN?
function getPflanzen($connection) {
    $sqlStmt = "SELECT * FROM pflanze";
    $result =  mysqli_query($connection, $sqlStmt);

    $data = array();

    if ($result = $connection->query($sqlStmt)) {
      while ($row = $result->fetch_assoc()) {

        $id = $row["id"];
        $zier = $row["zierbau"];
        $gala = $row["galabau"];
     
        array_push($data,array("id"=> $id, "zierplanzenbau"=>$zier, "gartenlandschaftsbau"=>$gala));
      }

      genJson($data); //Verarbeitung zu json
    }
    else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}



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

?>