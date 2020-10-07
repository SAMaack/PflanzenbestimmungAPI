<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

function getAzubis ($connection) {
    $sqlStmt = "SELECT az.id, az.fk_quiz_art, ad.name, az.nutzername, az.name, 
                       az.vorname, az.pruefung, aa.name as ausbildungsart, fa.name as fachrichtung, (CONCAT(ad.vorname,' ', ad.name)) AS ausbilder  
                FROM azubis az, ausbildungsart aa, fachrichtung fa, admins ad
                WHERE aa.id = az.fk_ausbildungsart AND az.fk_fachrichtung = fa.id AND ad.id = az.fk_ausbilder";
    $result =  mysqli_query($connection, $sqlStmt);
  
    $data = array();
  
    if ($result = $connection->query($sqlStmt)) {
        while ($row = $result->fetch_assoc()) {
  
            $id = $row["id"];
            $idquizart = $row["fk_quiz_art"];
            $nutzer = $row["nutzername"];
            $name = $row["name"];
            $vorname = $row["vorname"];
            $ausbilder = $row["ausbilder"];
            $ausbildungsart = $row["ausbildungsart"];
            $fachrichtung= $row["fachrichtung"];
            $pruefung= $row["pruefung"];
      
            array_push($data, array("id"=>$id, "name"=>$name, "vorname" =>$vorname, "nutzername"=>$nutzer, 
                                     "ausbildungsart"=>$ausbildungsart, "ausbilder"=>$ausbilder, "fachrichtung"=>$fachrichtung, "id_quiz_art"=>$idquizart, "pruefung"=>$pruefung));
          }
  
      genJson($data);
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
  }

///////// CREATE
function createAzubi($connection, $ausbilder, $ausbildungsart, $fachrichtung, $nutzername, $pw, $name, $vorname) {
  
  $sqlStmt = "INSERT into azubis(nutzername, passwort, vorname, name, fk_ausbilder, fk_ausbildungsart, fk_fachrichtung) 
              values ('$nutzername', '$pw', '$vorname', '$name', '$ausbilder', '$ausbildungsart', '$fachrichtung')";

  if (!$connection->query($sqlStmt)){
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

///////// UPDATE
function updateAzubi($connection, $args) {
  
  $sqlStmt = "UPDATE azubis SET ";

  for ($i = 1; $i < count($args); $i += 2) {
    $i2 = $i + 1; //Zur selektierung von zweitem Paramter.

    //Parameter Check
    if ($args[$i] == 'fk_ausbilder' ||         
        $args[$i] == 'fk_ausbildungsart' ||
        $args[$i] == 'fk_quizart' || 
        $args[$i] == "nutzername" || 
        $args[$i] == 'passwort' || 
        $args[$i] == 'name' || 
        $args[$i] == 'vorname' || 
        $args[$i] == 'pruefung') {

      //Statement Erweiterung, solange Parameter vorhanden.
      $sqlStmt .= "$args[$i] = '$args[$i2]'"; //$args[$i] = Ziel | $args[$i2] = Wert  || z.B.: name = Banane  
    }

    //Addition von Seperator, falls noch mehr Argumente vorhanden.
    if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
    }
  }
  
  $sqlStmt .= " WHERE id = '$args[0]'";

  //Error-Ausgabe, falls nicht Erfolgreich
  if (!$connection->query($sqlStmt)){
    echo mysqli_error($connection);     
  }

  closeConnection($connection); //Verbindung schließen 
} // FUNKTIONS ENDE

// DELETE
function deleteAzubi($connection, $IDaz) {
  $sqlStmt = "DELETE FROM azubis WHERE id = '$IDaz'";

  if (!$connection->query($sqlStmt)){
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}
?>