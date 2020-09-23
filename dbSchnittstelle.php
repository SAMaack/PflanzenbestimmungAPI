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

$method =  $_POST['method']; 

switch ($method) {
  case 'login':
    $username = $_POST['User'];
    $pw = $_POST['PW'];

    getLogin($connection,$username,$pw);
  break;


  //////// ADMIN //////// 
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

  //////// AZUBI //////// 
  case 'getAzubis':
    getAzubis($connection);
  break;

  case 'updateAzubi':   
    $args = array();
    foreach($_POST as $key => $value) {  // Als erster Parameter wird die ID des Azubis erwartet, danach dass oder die Ziele und Werte
        if ($key = 'IDa') {
          array_push($args, "fk_ausbilder", $_POST['IDa']);
        }
        else if($key = 'IDaa') {
          array_push($args, "fk_ausbildungsart", $_POST['IDaa']);
        }
        else if($key = 'IDqa') {
          array_push($args, "fk_quizart", $_POST['IDqa']);
        }
        else if($key = 'User') {
          array_push($args, "nutzername", $_POST['User']);
        }
        else if($key = 'PW') {
          array_push($args, "nutzername", $_POST['PW']);
        }
        else if($key = 'Name') {
          array_push($args, "name", $_POST['Name']);
        }
        else if($key = 'Vorname') {
          array_push($args, "vorname", $_POST['Vorname']);
        }
        else if($key = 'Bool_Pruefung') {
          array_push($args, "pruefung", $_POST['Bool_Pruefung']);
        }
    }; 

    updateAzubi($connection, $args);
  break;

  //////// KATEGORIEN //////// 

  case 'getKategorien':
    getKategorien($connection);
  break;

  case 'createKategorie':
    $kat_name  = $_POST['Kategorie'];
    createKategorie($connection, $kat_name);
  break;

  case 'updateKategorie':
    $id  = $_POST['IDk'];
    $kat_name  = $_POST['Kategorie'];
    updateKategorien($connection, $id, $kat_name);
  break;

  case 'deleteKategorie':
    $id  = $_POST['IDk'];
    deleteKategorien($connection, $id);
  break;

  //////// PFLANZEN //////// 

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

  //////// PFLANZEN - BILDER //////// 
  case 'getPBilder':
    $id_pflanze = $_POST['IDpb'];
    getPBilder($connection, $id_pflanze);
  break;

  case 'createPBild':
    $id_pflanze = $_POST['IDp'];
    $bild = $_POST['Bild'];
    createPBild($connection, $id_pflanze, $bild);
  break;

  case 'deletePBild':
    $id_bild = $_POST['IDpb'];
    deletePBild($connection, $id_bild);
  break;

  //////// STATISTIK //////// 

  case 'getStatistik':
    $id_azubi  = $_POST['IDa'];

    getStatistik($connection, $id_azubi);
  break;

  case 'createStatistik':
    $id_azubi  = $_POST['IDa'];
    $fehlerquote = $_POST['FQuote'];
    $quizzeit = $_POST['Zeit'];
    $id_pflanze  = $_POST['IDp'];

    createStatistik($connection, $id_azubi, $fehlerquote, $quizzeit, $id_pflanze);
  break;

  case 'createStatEinzel':
    $id_stat  = $_POST['IDs'];
    $id_kategorie = $_POST['IDk'];
    $id_pflanze  = $_POST['IDp'];


    createStatEinzel($connection, $id_stat, $id_pflanze, $id_kategorie);
  break;

  case 'createStatEinzelDetail':
    $id_stat  = $_POST['IDs'];
    $id_kategorie = $_POST['IDk'];
    $id_pflanze  = $_POST['IDp'];
    $eingabe = $_POST['Eingabe'];

    createStatEinzelDetail($connection, $id_stat, $id_pflanze, $id_kategorie, $eingabe);
  break;

  case 'updateStatistik':
    $id_stat  = $_POST['IDs'];
    $fehlerquote  = $_POST['FQuote'];
    $quizzeit  = $_POST['Zeit'];
    $id_pflanze  = $_POST['IDp'];

    updateStatistik($connection, $id_stat, $fehlerquote, $quizeit, $id_pflanze);

  break;

  //////// STATISTIK - DETAILS //////// 
  case 'getStatDetails':
    $id_stat = $_POST['IDs'];

    getStatDetails($connection, $id_Stat);
  break;  

  case 'createStatDetails':

    //ÜBERGABE WERTE
    $id_stat = $_POST['IDs'];
    $id_pflanze = $_POST['IDp'];
    $id_kategorie = $_POST['IDk'];
    $zeit = $_POST['Zeit'];
    $eingabe = $_POST['Eingabe'];
 
    //AUFRUF VON FUNKTION
    createStatDetails($connection, $id_stat, $id_pflanze, $id_kategorie, $zeit, 
                                   $eingabe);
  break;

  //////// QUIZ - ART ////////
  case 'getQuizArt':
    getQuizArt($connection);
  break;

  case 'createQuizArt':
    $quizname = $_POST['Quizname'];
    $groeße = $_POST['Groeße'];
    createQuizArt($connection, $quizname, $groeße);
  break;

  case 'updateQuizArt':
    $id_qart = $_POST['IDqa'];
    $quizname = $_POST['Quizname'];
    $groeße = $_POST['Groeße'];
    updateQuizArt($connection, $id_qart, $quizname, $quizgroeße);
  break;

  case 'deleteQuizArt':
    $id_qart = $_POST['IDqa'];
    deleteQuizArt($connection, $id_qart);
  break;

  //////// QUIZ - PLANZEN - ZUWEISUNG ////////
  case 'getQuizPZuweisung':
    $id_azubi = $_POST['IDa'];
    getQuizPZuweisung($connection, $id_azubi);
  break;

  case 'createQuizPZuweisung':
    $id_azubi = $_POST['IDa'];
    $id_pflanze = $_POST['IDp'];
    createQuizPZuweisung($connection, $id_azubi, $id_pflanze);
  break;

  case 'deleteQuizPZuweisung':
    $id_azubi = $_POST['IDa'];
    $id_pflanze = $_POST['IDp'];
    deleteQuizPZuweisung($connection, $id_azubi, $id_pflanze);
  break;
} 

//Umwandeln in und Ausgabe von Daten als JSON
function genJson($data) {
  $myJSON = json_encode($data);
    echo $myJSON;
}
 
?>