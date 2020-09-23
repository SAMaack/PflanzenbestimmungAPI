<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

function getLogin($connection,$username,$pw){

    //Abfrage Azubis
    $sqlStmt = "SELECT * FROM azubis where nutzername = '$username' AND passwort = '$pw'";
    $result =  mysqli_query($connection,$sqlStmt);  
    $azubis = array();
    
    if ($result1 = $connection->query($sqlStmt)) {
  
      while ($row = $result1->fetch_assoc()) {
        $id = $row["id"];
        $name = $row["name"];
        $vorname = $row["vorname"];
        $ausbilder = $row["fk_ausbilder"];
        $ausbildungsart = $row["fk_ausbildungsart"];
        $fachrichtung=$row["fk_fachrichtung"];
        $quizart = $row["fk_quiz_art"];
        $pruefbool = $row["pruefung"];
        
  
        array_push($azubis,array("id"=>$id, "name"=>$name, "vorname" =>$vorname, "id_ausbilder"=>$ausbilder, 
                                 "id_ausbildung"=>$ausbildungsart,"id_fachrichtung"=>$fachrichtung, "id_quiz_art"=>$quizart, "bool_pruefung"=>$pruefbool)); 
      }
    }
    else{
      echo mysqli_error($connection);
    }
  
    //Abfrage Admins
    $sqlStmt2 = "SELECT * FROM admins where nutzername = '$username' AND passwort = '$pw'";
    $result2 = mysqli_query($connection, $sqlStmt2);
    $admins = array();
  
    if ($result2 = $connection->query($sqlStmt2)){
  
      while ($row = $result2->fetch_assoc()) {
          $id = $row["id"];
          $nutzer = $row["nutzername"];
          $berflag = $row["berflag"];
  
          array_push($admins,array("id"=>$id, "nutzername"=>$nutzer, "berflag"=>$berflag));
        
      }
    }
  
    else{
      echo mysqli_error($connection);
    }
    $result1->free();
    $result2->free();
    closeConnection($connection);
  
  
    //Verarbeitung zu json
    if ($azubis != []) {  
        genJson($azubis);
    }
    else if ($admins != []) {
        genJson($admins);
    } 
  }
?>