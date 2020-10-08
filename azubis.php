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
function createAzubi($connection, $ausbilder, $ausbildungsart, $fachrichtung, $nutzername, $pw, $name, $vorname, $pruefung, $quiz) {
  
  $sqlStmt = "INSERT into azubis(nutzername, passwort, vorname, name, fk_ausbilder, fk_ausbildungsart, fk_fachrichtung, pruefung, fk_quiz_art) 
              values ('$nutzername', '$pw', '$vorname', '$name', '$ausbilder', '$ausbildungsart', '$fachrichtung', '$pruefung', '$quiz')";

  if (!$connection->query($sqlStmt)){
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

///////// UPDATE
function updateAzubi($connection, $args) {
  
  $sqlStmt = "UPDATE azubis SET";

  $ausbI = false;
  $ausbA = false;
  $quiz = false;
  $nutzer = false;
  $pass = false;
  $name = false;
  $vname = false;
  $pruef = false;
  $fach = false;

  for ($i = 1; $i < count($args); $i++) {
    $i2 = $i + 1; //Zur selektierung von zweitem Paramter.

    //Parameter Check
    if ($args[$i] == "fk_ausbilder" && !$ausbI) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $ausbI = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    }         
    elseif ($args[$i] == "fk_ausbildungsart" && !$ausbA) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $ausbA = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.Und 
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    }
    elseif ($args[$i] == "fk_quiz_art" && !$quiz) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $quiz = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    }
    elseif ($args[$i] == "fk_fachrichtung" && !$fach) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $fach = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    }
    elseif ($args[$i] == "nutzername" && !$nutzer) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $nutzer = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    }
    elseif ($args[$i] == "passwort" && !$pass) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $pass = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    }
    elseif ($args[$i] == "name" && !$name) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $name = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    }
    elseif ($args[$i] == "vorname" && !$vname) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $vname = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    } 
    elseif ($args[$i] == "pruefung" && !$pruef) {
      $sqlStmt .= " $args[$i] = '$args[$i2]'";
      $pruef = true;

      //Addition von Seperator, falls noch mehr Argumente vorhanden.
      if ($i < count($args) - 2) {
      $sqlStmt .= ", ";
      }
    }
  }// END FOR

  $ausbI = false;
  $ausbA = false;
  $quiz = false;
  $nutzer = false;
  $pass = false;
  $name = false;
  $vname = false;
  $pruef = false;
  $fach = false;

  $sqlStmt .= " WHERE id = '$args[0]'";
  echo $sqlStmt;
  
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