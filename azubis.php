<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

function getAzubis ($connection) {
    $sqlStmt = "SELECT az.id, az.fk_quiz_art, az.fk_ausbilder, az.nutzername, az.name, 
                       az.vorname, az.pruefung, aa.name as ausbildungsart, fa.name as fachrichtung 
                FROM azubis az, ausbildungsart aa, fachrichtung fa
                WHERE aa.id = az.fk_ausbildungsart AND az.fk_fachrichtung = fa.id";
    $result =  mysqli_query($connection, $sqlStmt);
  
    $data = array();
  
    if ($result = $connection->query($sqlStmt)) {
        while ($row = $result->fetch_assoc()) {
  
            $id = $row["id"];
            $idquizart = $row["fk_quiz_art"];
            $nutzer = $row["nutzername"];
            $name = $row["name"];
            $vorname = $row["vorname"];
            $ausbildungsart = $row["ausbildungsart"];
            $fachrichtung= $row["fachrichtung"];
            $pruefung= $row["pruefung"];
      
            array_push($data, array("id"=>$id, "name"=>$name, "vorname" =>$vorname, "nutzername"=>$nutzer, 
                                     "ausbildung"=>$ausbildungsart,"fachrichtung"=>$fachrichtung, "quiz-art"=>$idquizart, "pruefung"=>$pruefung));
          }
  
      genJson($data);
    }else{
      echo mysqli_error($connection);
    }
    closeConnection($connection);
  }

  //create

//CREATE

function createAzubi($connection, $ausbilder, $ausbildungsart, $fachrichtung, $nutzername, $pw, $name, $vorname) {
  $sqlStmt = "INSERT into azubis(nutzername, passwort, vorname, name, fk_ausbilder, fk_ausbildungsart, fk_fachrichtung) 
  values ('$nutzername', '$pw', '$vorname', '$name', '$ausbilder', '$ausbildungsart', '$fachrichtung')";

              //Error-Abfrage?
  $connection->query($sqlStmt);
  closeConnection($connection);
}
?>