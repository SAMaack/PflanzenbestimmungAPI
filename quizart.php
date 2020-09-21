<?php
$reportingLevel = -1; //0 für alle PHP Fehler und Warungen ausblenden, -1 für alle anzeigen
error_reporting($reportingLevel); 

//////// GET
function getQuizArt($connection) {
    $sqlStmt = "SELECT * FROM quiz_art";

    if (!$connection->query($sqlStmt)) {
        echo mysqli_error($connection);
    }
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

//////// UPDATE -- TODO
function updateQuizArt($connection) {
    $sqlStmt = "";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}

//////// DELETE -- TODO
function deleteQuizArt($connection) {
    $sqlStmt = "";

    if (!$connection->query($sqlStmt)) {
      echo mysqli_error($connection);
    }
    closeConnection($connection);
}



?>