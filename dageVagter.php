<?php

$month = date("n");
$year = date("Y");

if (!empty($_COOKIE["Month"] && !empty($year = $_COOKIE["Year"]))) {
  $month = $_COOKIE["Month"] + 1;
  $year = $_COOKIE["Year"];
}

$dage = [];
$currentDag = 0;
$id = $_SESSION["id"];

$sql = "SELECT user_id, dag, vagt_accepteret FROM vagter WHERE month = ? AND year = ? ORDER BY dag";
require_once "config.php";
if($stmt = mysqli_prepare($link, $sql)){

    // Attempt to execute the prepared statement
    mysqli_stmt_bind_param($stmt, "ii", $param_month, $param_year);
      // Attempt to execute the prepared statement

    $param_month = $month;
    $param_year = $year;
    if(mysqli_stmt_execute($stmt)){
      mysqli_stmt_store_result($stmt);

      if(mysqli_stmt_num_rows($stmt) >= 1){
        mysqli_stmt_bind_result($stmt, $user_id, $dag, $vagt_accepteret);
        while (mysqli_stmt_fetch($stmt)) {
          if ($dag != $currentDag) {
            if ($user_id == $id || $vagt_accepteret == 0) {


              $currentDag = $dag;
              array_push($dage, $dag);
            }
          }

        }

      }

    }
  }







?>
