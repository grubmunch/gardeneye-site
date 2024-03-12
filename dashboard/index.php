<?php
session_start();
include "../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo $_SESSION["logged_in"];

?>

