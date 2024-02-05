<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$vagt_id = $_GET['vagt_id'];
$user_id = $_SESSION["id"];
require_once "config.php";
$sql = "SELECT vagt_accepteret FROM vagter WHERE id = ? AND user_id = ?";

if($stmt = mysqli_prepare($link, $sql)){
  mysqli_stmt_bind_param($stmt, "ss", $param_vagt_id, $param_user_id);
    // Attempt to execute the prepared statement
    $param_vagt_id = $vagt_id;
    $param_user_id = $user_id;
    if(mysqli_stmt_execute($stmt)){
        // Store result
      mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) == 1){

            // Bind result variables
            mysqli_stmt_bind_result($stmt, $vagt_accepteret);
            if (mysqli_stmt_fetch($stmt)) {
              if ($vagt_accepteret == 0) {
                $sql1 = "DELETE FROM vagter WHERE id= ?;";

                if($stmt = mysqli_prepare($link, $sql1)){

                    // Attempt to execute the prepared statement
                    mysqli_stmt_bind_param($stmt, "s", $param_vagt_id);
                      // Attempt to execute the prepared statement

                      $param_vagt_id = $vagt_id;
                    if(mysqli_stmt_execute($stmt)){
                      header("location: https://fischer.thomashegelund.dk/");
                    }
                  }

              } else{
                $_SESSION["err"] = "Din vagt er blevet accepteret af en anden bruger. Kontakt ledelsen for at stoppe vagtbyttet.";
                header("location: https://fischer.thomashegelund.dk");
              }

            }
          }
        }
      }


?>
