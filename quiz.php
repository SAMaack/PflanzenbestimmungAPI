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

        $sqlStmt = "SELECT * FROM quiz_p_zuweisung WHERE fk_quiz_art = '$id'";

        $pflanzen = array();

        if ($result2 = $connection->query($sqlStmt)) {
          while ($row = $result2->fetch_assoc()) {
            $id_pflanze = $row["fk_pflanze"];         
            array_push($pflanzen, array("id_pflanze"=>$id_pflanze));
          }
        }
        else {
          echo mysqli_error($connection);
        }
        array_push($data, array("id"=>$id, "quizname"=>$quizname, "quizgroeße"=>$quizgroeße, "pflanzen"=>$pflanzen));
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
    else {
      $sqlStmt = "SELECT LAST_INSERT_ID() as id";
      $data = array();
      if ($result = $connection->query($sqlStmt)) {
        while ($row = $result->fetch_assoc()) {
          array_push($data, array("ID_QuizArt"=>$row["id"]));
        }
        $result->free();
        genJson($data);
      }
      else {
         echo mysqli_error($connection);
      }
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

//////// INSERT
function createQuizPZuweisung($connection, $id_quiz_art, $id_pflanze) {
  $sqlStmt = "INSERT INTO quiz_p_zuweisung (fk_azubi, fk_pflanze) 
              VALUES ('$id_quiz_art', '$id_pflanze')";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}

//////// DELETE
function deleteQuizPZuweisung($connection, $id_quiz_art, $id_pflanze) {
  $sqlStmt = "DELETE FROM quiz_p_zuweisung WHERE fk_azubi = '$id_quiz_art' AND fk_pflanze = '$id_pflanze'";

  if (!$connection->query($sqlStmt)) {
    echo mysqli_error($connection);
  }
  closeConnection($connection);
}
?>