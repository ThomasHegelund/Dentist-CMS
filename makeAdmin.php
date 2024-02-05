<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}elseif (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== 1) {
  header("location: https://fischer.thomashegelund.dk/");
  exit;
}



$id = $_GET['id'];
require_once 'config.php';

$sql1 = "UPDATE users SET admin = 1 WHERE id= ?;";

if($stmt = mysqli_prepare($link, $sql1)){

    // Attempt to execute the prepared statement
    mysqli_stmt_bind_param($stmt, "s", $param_id);
      // Attempt to execute the prepared statement

      $param_id = $id;
    if(mysqli_stmt_execute($stmt)){
      header("location: https://fischer.thomashegelund.dk/administrerBrugere.php");
    }
  }


?>
