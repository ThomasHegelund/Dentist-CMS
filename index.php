<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$_SESSION['url'] = "";
require_once 'dageVagter.php';
?>


<!DOCTYPE html>
<head>

    <meta charset="UTF-8">
    <title>Kalender</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="js/jquery.timeentry.css">
    <script src="js/jquery.plugin.js"></script>

    <script src="js/jquery.timeentry.js">

    </script>

</head>
<body>


    <?php
    // Initialize the session
    session_start();
    $admin = $_SESSION["admin"];
    // Check if the user is logged in, if not then redirect him to login page
    if($_SESSION["admin"] == 1){
      ?>
      <div class='header'>
        <div class="adminHeader">
          <a href='/vagtBytte.php' class='headerButton admin'>Administrer vagtbytte</a>
          <a href='/administrerBrugere.php' class='headerButton admin'>Administrer rettigheder</a>
          <a href='' class='headerButton admin'>Kalender</a>
        </div>
        <div id="account">
          <a onclick = "logout()"class="accountButtons" id="logout" href="logout.php"><i class="fa fa-sign-out"></i></a>
          <a class="accountButtons" id="aMenu" href="/menu.php"><i class="fa fa-bars"></i></a>
          <a class="accountButtons" href="/dineOplysninger.php" id="nameLabel"><?php echo htmlspecialchars($_SESSION["name"]); ?></a>
        </div>
      </div>
        <?php
    } else {
      ?>
      <div class="header">
      <div id="account">
        <a onclick = "logout()"class="accountButtons" id="logout" href="logout.php"><i class="fa fa-sign-out"></i></a>
        <a class="accountButtons" href="/dineOplysninger.php" id="nameLabel"><?php echo htmlspecialchars($_SESSION["name"]); ?></a>
      </div>
    </div>
      <?php
    }
    ?>




<div class="container col-sm-4 col-md-7 col-lg-4 mt-5">
    <div class="card">

      <div class="form-inline">
        <div class="card-header-left">
          <h3 class="card-header" id="monthAndYear"></h3>
        </div>

          <div class="card-header-right">
            <button class="card-headerButton"id="previous" onclick="previous()">Forrige</button>
            <button class="card-headerButton"id="next" onclick="next()">Næste</button>
          </div>

      </div>

        <table class="table table-bordered table-responsive-sm" id="calendar">
            <thead>
            <tr style="background-color:#97A5B0; color:white;">
                <th class="calendarDateHeader">Man</th>
                <th class="calendarDateHeader">Tir</th>
                <th class="calendarDateHeader">Ons</th>
                <th class="calendarDateHeader">Tor</th>
                <th class="calendarDateHeader">Fre</th>
                <th class="calendarDateHeader">Lør</th>
                <th class="calendarDateHeader">Søn</th>
            </tr>
            </thead>

            <tbody id="calendar-body">

            </tbody>
        </table>

    </div>

</div>


</body>



</html>
<?php

if ($_COOKIE["dato"] != "") {
  require_once 'vagter.php';
}

require 'scripts.php';

if ($_SESSION["err"] != "") {
  ?>
  <script  type="text/javascript">
  window.alert("<?php echo $_SESSION['err'];?>");
  </script>

  <?php
  $_SESSION["err"] = "";
}
?>

<!-- Optional JavaScript for bootstrap -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>
