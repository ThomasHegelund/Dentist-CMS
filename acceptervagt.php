<?php
session_start();

require_once "config.php";

$sql = "SELECT user_id, vagt_accepteret FROM vagter WHERE id = ?";
$err = "";
$id = $_GET['id'];
if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $param_id);
    // Set parameters
    $param_id = $id;
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Store result
      mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) == 1){

            // Bind result variables
            mysqli_stmt_bind_result($stmt, $user_id, $vagt_accepteret);

            if (mysqli_stmt_fetch($stmt)) {
              if ($vagt_accepteret == 0) {
                $sql2 = "INSERT INTO vagter_accepterede (user1_id, user2_id, vagt_id) VALUES (?, ?, ?)";
                if($stmt2 = mysqli_prepare($link, $sql2)){

                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt2, "iii", $param_user1_id, $param_user2_id, $param_vagt_id);

                    // Set parameters
                    $param_user1_id = $user_id;
                    $param_user2_id = $_SESSION["id"];
                    $param_vagt_id = $id;


                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt2)){
                        // Redirect to login page

                        $sql3 = "UPDATE vagter SET vagt_accepteret = 1 WHERE id =  $id";
                        if($stmt3 = mysqli_prepare($link, $sql3)){
                          if(mysqli_stmt_execute($stmt3)){

                          }
                        }
                      }

                    // Close statement
                    mysqli_stmt_close($stmt2);
                }
              }else {
                $_SESSION["err"] = "Vagten er allerede blevet accepteret.";
              }

            }
          }
          else {
            $_SESSION["err"] = "Vagten er desværre blevet fjernet.";
          }
          mysqli_stmt_close($stmt);

        }
        mysqli_close($link);
      }


      header("location: https://fischer.thomashegelund.dk/");

?>
