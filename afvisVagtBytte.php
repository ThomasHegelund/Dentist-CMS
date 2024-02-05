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
$accepteret_vagt_id = $_GET['accepteret_vagt_id'];
require_once "config.php";
$sql = "SELECT vagter.dag, vagter.month, vagter.year, vagter.tid, users1.name, users1.email, users2.name, users2.email FROM vagter vagter, users users1, users users2, vagter_accepterede vagter_accepterede WHERE vagter_accepterede.id = ? AND vagter.id = vagter_accepterede.vagt_id AND users1.id = vagter_accepterede.user1_id AND users2.id = vagter_accepterede.user2_id";

if($stmt = mysqli_prepare($link, $sql)){
  mysqli_stmt_bind_param($stmt, "s", $param_accepteret_vagt_id);
    // Attempt to execute the prepared statement
    $param_accepteret_vagt_id = $accepteret_vagt_id;
    if(mysqli_stmt_execute($stmt)){
        // Store result
      mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) >= 1){

            // Bind result variables
            mysqli_stmt_bind_result($stmt, $dag, $month, $year, $tid, $name1, $email1, $name2, $email2);
            while (mysqli_stmt_fetch($stmt)) {
              $headers = "From: fischer@thomashegelund.dk";

              $msg1 = "Kære ". $name1 ."\r\n"."Dit vagtbytte den " .$dag ."/".$month."-".$year." klokken ".$tid." med " . $name2 ." er blevet afvist af administrationen.";
              $subject1 = "Vagtbytte den ".$dag ."/".$month."-".$year." afvist.";

              $msg2 = "Kære ".$name2 ."\r\n"."Administrationen ikke tilladt, at du overtager vagten den " .$dag ."/".$month."-".$year." klokken ".$tid." fra " .$name1.".";
              $subject2 = "Vagtbytte den ".$dag ."/".$month."-".$year." afvist.";
              mail($email1,$subject1,$msg1,$headers);
              mail($email2,$subject2,$msg2,$headers);


            }
          }
        }
      }

      $sql1 = "DELETE vagter_accepterede, vagter FROM vagter_accepterede as vagter_accepterede INNER JOIN  vagter as vagter on vagter_accepterede.vagt_id = vagter.id WHERE vagter_accepterede.id= ?;";

      if($stmt = mysqli_prepare($link, $sql1)){

          // Attempt to execute the prepared statement
          mysqli_stmt_bind_param($stmt, "s", $param_accepteret_vagt_id);
            // Attempt to execute the prepared statement

            $param_accepteret_vagt_id = $accepteret_vagt_id;
          if(mysqli_stmt_execute($stmt)){
            header("location: https://fischer.thomashegelund.dk/vagtBytte.php");
          }
        }
?>
