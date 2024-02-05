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

?>

<head>
    <meta charset="UTF-8">
    <title>Menu</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>

  <div class='header'>
    <div id="account">
      <a onclick = "logout()"class="accountButtons" id="logout" href="logout.php"><i class="fa fa-sign-out"></i></a>
      <a class="accountButtons" id="aMenu" href="https://fischer.thomashegelund.dk"><i class="fa fa-bars"></i></a>
      <a class="accountButtons" href="/dineOplysninger.php" id="nameLabel"><?php echo htmlspecialchars($_SESSION["name"]); ?></a>
    </div>
  </div>

  <div id="menuDiv">
    <div id="innerMenuDiv">
      <div class="menuButtonDiv">
        <a href='/vagtBytte.php' class='headerButton admin'>Adm. vagtbytte</a>
      </div>
      <div class="menuButtonDiv">
      <a href='/administrerBrugere.php' class='headerButton admin'>Adm. rettigheder</a>
      </div>
      <div class="menuButtonDiv">
      <a href='https://fischer.thomashegelund.dk/' class='headerButton admin'>Kalender</a>
      </div>
    </div>

  </div>

</body>

<script type="text/javascript">
window.onload = checkWidth;
window.onresize = checkWidth;
  function checkWidth(){
    if (window.innerWidth > 693) {
      window.location.href = "https://fischer.thomashegelund.dk/";
    }
  }
</script>
