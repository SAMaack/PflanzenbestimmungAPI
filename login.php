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
        $name = $row["name"];
        $vorname = $row["vorname"];
        $ausbildungsart = $row["fk_ausbildungsart"];
        $fachrichtung=$row["fk_fachrichtung"];
  
        array_push($azubis,array("name"=>$name, "vorname" =>$vorname,
                                 "ausbildung"=>$ausbildungsart,"fachrichtung"=>$fachrichtung));
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
          $nutzer = $row["nutzername"];
          $berflag = $row["berflag"];
  
          array_push($admins,array("nutzername"=>$nutzer, "berflag"=>$berflag));
      }
    }
  
    else{
      echo mysqli_error($connection);
    }
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