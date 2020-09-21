<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

$connection = getDBConnection();
function getDBConnection(){
  //Einstellungen der Datenbank
  $dbusername = 'root';         //Benutzername
  $dbpassword = '';             //Passwort
  $dburl='localhost';           //URL
  $dbname='Pflanzenbestimmung'; //Datenbankname

  $fehler1 = "Fehler 1: Fehler beim aufbauen der Datenbankverbindung!";
    $link = mysqli_connect($dburl, $dbusername, $dbpassword,$dbname);
    if (!$link) {
        die('Verbindung schlug fehl: ' . mysqli_error());
    }
  
  /* check connection */
  if (mysqli_connect_errno()) {
       die($fehler1);
  }
  return $link;
}

function closeConnection($connection){
  mysqli_close($connection);
}
?>