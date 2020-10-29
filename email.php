<?php
$empfaenger = "amir.kurbanov@tsbw.cloud";
$betreff = "Bug-Report - Pflanzenbestimmung";
$from = "From: Aufmerksamer Nutzer <nutzer>";
$text = $_POST["text"];
echo $text;
mail($empfaenger, $betreff, $text, $from);
?>