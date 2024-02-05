<?php
$id = "";
$url = "";
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
} else {
  $id = $_SESSION['id'];
  $url = "login.php";
}

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== 1) {
} else {
  $id = $_GET['id'];
  $url ="https://fischer.thomashegelund.dk/administrerBrugere.php";
}


require_once 'config.php';
$sql1 = "DELETE users, vagter_accepterede, vagter FROM users as users INNER JOIN vagter as vagter on users.id = vagter.user_id INNER JOIN vagter_accepterede as vagter_accepterede on vagter_accepterede.user1_id = users.id OR vagter_accepterede.user2_id = users.id WHERE users.id= ?;";
$sql = "DELETE FROM users WHERE id= ?";

if($stmt = mysqli_prepare($link, $sql)){

    // Attempt to execute the prepared statement
    mysqli_stmt_bind_param($stmt, "i", $param_id);
      // Attempt to execute the prepared statement
      $param_id = $id;
    if(mysqli_stmt_execute($stmt)){
      if ($url ==  "login.php") {
        session_destroy();
        ?>
        <script type="text/javascript">
        alert("Din bruger er blevet slettet")
        window.location.href = "https://fischer.thomashegelund.dk/login.php";
        </script>

        <?php
        exit();
      }
      header("location: $url");
    }
  }


?>
