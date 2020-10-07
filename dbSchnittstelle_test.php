<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

INCLUDE ('db.php');
INCLUDE ('login.php');
INCLUDE ('admins.php');
INCLUDE ('azubis.php');
INCLUDE ('pflanzen.php');
INCLUDE ('statistik.php');
INCLUDE ('quiz.php');
INCLUDE ('kategorien.php');
INCLUDE ('abgefragt.php');
INCLUDE ('fachrichtung.php');
INCLUDE ('ausbildungsart.php');

//getFachrichtungen($connection);
//getAusbildungsart($connection);

//getPBilder($connection, 1);

//getPflanzen($connection);
//getQuizPZuweisung($connection, 1);

//getLogin($connection, "BeispielAdmin", "BeispielPasswort");

//getAbgefragt($connection, 4);

getAzubis($connection);
//getAdmins($connection);

//$args = array();
//array_push($args, 5, "nutzername", "UpdateTest", "passwort", "test");
//updateAzubi($connection, $args);
//createAzubi($connection,,,,,,,,,);

//getStatistik($connection, 1);
//testStat($connection, 1);
//getStatDetails($connection, 1);

//createAdmin($connection, "ApiTest", "Test");
//updateAdmin($connection, 2, "UpdateTest", "Test");

//createStatistik($connection, 1 , "2000", '5:5:50', 1);
//createStatDetails($connection, 3, 2, '0:0:30', 0, 1, 1, 1, 1, 1, 1, 1, 0, 1, "Gatsdadsg X","Artasdasdasd B", "DE NAasdasdasdME B", "Fasdasdasam B", "Herkuasdasdasnft B", "Bluetasdasdasde B", "Buetezeiasdasdasdt B", "Blaasdasdtt B", "Wuasdasdasdchs X", "Besondasdasdasderheiten B");
//getKategorien($connection);
//createKategorie($connection, "spannendeTestKategorie");

//createQuizArt($connection, "TestQuiz2", 25);
//updateQuizArt($connection, 1, "TestQuizUpdated", 51);
//deleteQuizArt($connection, 2);
//getQuizArt($connection, 1);

//Connection, Pflanze(Zierbau), Pflanze(Galabau) | -> FK_P_Kategorie_ID, P_Antworten_Antwort
/*createPflanze($connection, 0, 1, 1, "GattungTest", 
                                 2, "ArtTest", 
                                 3, "DeTest", 
                                 4, "FamTest", 
                                 5, "HerkTest", 
                                 6, "BlueteTest", 
                                 7, "BlueteZTest", 
                                 8, "BlazzTest", 
                                 9, "WuchsTest", 
                                 10, "BesondTest", 
                                 11, "LebensTest", 
                                 12, "WuchsFormenTest", 
                                 13, "StandTest", 
                                 14, "VTest"); 
$args = array();
array_push($args, 0, 1, 
1, "GattungTest", 
2, "ArtTest", 
3, "DeTest", 
4, "FamTest", 
5, "HerkTest", 
6, "BlueteTest", 
7, "BlueteZTest", 
8, "BlazzTest", 
9, "WuchsTest", 
10, "BesondTest", 
11, "LebensTest", 
12, "WuchsFormenTest", 
13, "StandTest", 
14, "VTest");                                 
createPflanze($connection, $args); */

/*
array(1) { 
  [0]=> array(30) { 
    [0]=> int(0) 
    [1]=> int(1) 
    [2]=> int(1) 
    [3]=> string(11) "GattungTest" 
    [4]=> int(2) 
    [5]=> string(7)...
*/
/*$method =  $_POST['method'];  

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
}  */

//Umwandeln und Ausgabe von Daten als JSON
function genJson($data) {
  $myJSON = json_encode($data);
    echo $myJSON;
}

?>
