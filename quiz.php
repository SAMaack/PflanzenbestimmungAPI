<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

//////// ////////  QUIZ - ART //////// //////// 
//////// GET
function getQuizArt($connection) {
    $sqlStmt = "SELECT * FROM quiz_art";

    $data = array();

    if ($result = $connection->query($sqlStmt)) {
      while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $quizname = $row["name"];
        $quizgroeße = $row["groeße"];
        
        array_push($data, array("id"=>$id, "quizname"=>$quizname, "quizgroeße"=>$quizgroeße));
      }

      genJson($data);
    }
    else {
      echo mysqli_error($connection);
    }
    $result->free();
    closeConnection($connection);
}

//////// INSERT
function createQuizArt($connection, $name, $groeße) {
    $sqlStmt = "INSERT INTO quiz_art (name, groeße) 
                VALUES ('$name', '$groeße')";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

//////// UPDATE
function updateQuizArt($connection, $id, $quizname, $groeße) {
    $sqlStmt = "UPDATE quiz_art SET name = '$quizname', groeße = '$groeße'
                WHERE id = '$id'";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

//////// DELETE
function deleteQuizArt($connection, $id) {
    $sqlStmt = "DELETE FROM quiz_art WHERE id = '$id'";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

//////// //////// QUIZ - FRAGEN //////// //////// 

function getQuizPZuweisung($connection, $id) {
  $sqlStmt = "SELECT * FROM quiz_p_zuweisung WHERE fk_azubi = '$id'";

  $data = array();

  if ($result = $connection->query($sqlStmt)) {
    while ($row = $result->fetch_assoc()) {
      $id_pflanze = $row["fk_pflanze"];
      
      array_push($data, array("id_pflanze"=>$id_pflanze));
    }

    genJson($data);
  }
  else {
    echo mysqli_error($connection);
  }
  $result->free();
  closeConnection($connection);
}

//////// INSERT
function createQuizPZuweisung($connection, $id_azubi, $id_pflanze) {
  $sqlStmt = "INSERT INTO quiz_p_zuweisung (fk_azubi, fk_pflanze) 
              VALUES ('$id_azubi', '$id_pflanze')";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

//////// DELETE
function deleteQuizPZuweisung($connection, $id_azubi, $id_pflanze) {
  $sqlStmt = "DELETE FROM quiz_p_zuweisung WHERE fk_azubi = '$id_azubi' AND fk_pflanze = '$id_pflanze'";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}
?>