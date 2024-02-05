<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$tid = $_GET['tid'];
$dato = $_GET['dato'];
$afdeling = $_GET['afd'];

$sql = "INSERT INTO vagter (user_id, dag, month, year, afdeling, tid) VALUES (?, ?, ?, ?, ?, ?)";
if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "iiiiss", $param_user_id, $param_dag, $param_month, $param_year, $param_afdeling, $param_tid);

    // Set parameters
    $param_user_id = $_SESSION["id"];
    $param_dag = explode("/",$dato)[0];
    $param_month = explode("/",$dato)[1];
    $param_year = explode("-",$dato)[1];
    $param_afdeling = $afdeling;
    $param_tid = $tid;

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        header("location: https://fischer.thomashegelund.dk/");
    } else{
        echo "Something went wrong. Please try again later.";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}


?>
