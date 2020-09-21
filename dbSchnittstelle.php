<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

INCLUDE ('db.php');
INCLUDE ('login.php');
INCLUDE ('admins.php');
INCLUDE ('azubis.php');
INCLUDE ('pflanzen.php');
INCLUDE ('statistik.php');

$method =  $_POST['method']; 

switch ($method) {
  case 'login':
    $username = $_POST['User'];
    $pw = $_POST['PW'];

    getLogin($connection,$username,$pw);
  break;

  //////// PFLANZEN
  case 'getPflanzen':
    getPflanzen($connection);
  break;

  case 'createPflanze':
    $args = array();
    foreach($_POST as $key => $value) {
      if (!$key = 'method') {
        array_push($args, $_POST[$key]);
      }
    }; 

    createPflanze($connection, $args);
  break;

  //////// ADMIN
  case 'getAdmins':
    getAdmins($connection);
  break;
  
  case 'createAdmin':
    $username = $_POST['User'];
    $pw = $_POST['PW'];

    createAdmin($connection, $nutzername, $pw);
  break;

  case 'updateAdmin':
    $id_admin  = $_POST['ID'];
    $username = $_POST['User'];
    $pw = $_POST['PW'];

    updateAdmin($connection, $id_admin, $username, $pw);
  break;

  //////// AZUBI
  case 'getAzubis':
    getAzubis($connection);
  break;

  //////// STATISTIK
  case 'getStatistik':
    $id_azubi  = $_POST['ID'];

    getStatistik($connection, $id_azubi);
  break;

  case 'createStatistik':
    $id_azubi  = $_POST['ID'];
    $fehlerquote = $_POST['quote'];
    $quizzeit = $_POST['ZEIT'];
    $id_pflanze  = $_POST['IDp'];

    createStatistik($connection, $id_azubi, $fehlerquote, $quizzeit, $id_pflanze);
  break;

  case 'updateStatistik':
    $id_stat  = $_POST['ID'];
    $fehlerquote  = $_POST['FEHLER'];
    $quizzeit  = $_POST['ZEIT'];
    $id_pflanze  = $_POST['IDp'];

    updateStatistik($connection, $id_stat, $fehlerquote, $quizeit, $id_pflanze);

  break;

  //////// STATISTIK - DETAILS
  case 'getStatDetails':
    $id_stat = $_POST['ID'];

    getStatDetails($connection, $id_Stat);
  break;  

  case 'createStatDetails':

    //ÜBERGABE WERTE
    $id_stat = $_POST['ID'];
    $id_pflanze = $_POST['IDp'];
    $id_kategorie = $_POST['IDk'];
    $zeit = $_POST['zeit'];
    $eingabe = $_POST['eingabe'];
 
    //AUFRUF VON FUNKTION
    createStatDetails($connection, $id_stat, $id_pflanze, $id_kategorie, $zeit, 
                                   $eingabe);
  break;
} 

//Umwandeln in und Ausgabe von Daten als JSON
function genJson($data) {
  $myJSON = json_encode($data);
    echo $myJSON;
}

?>