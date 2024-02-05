<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}elseif (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== 1) {
  header_remove('https://fischer.thomashegelund.dk/');
  exit;
}
$_SESSION['url'] = "administrerBrugere.php";

?>
<head>
    <meta charset="UTF-8">
    <title>Administrer brugere</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


</head>
<body>
  <div class='header'>
    <div class="adminHeader">
      <a href='/vagtBytte.php' class='headerButton admin'>Administrer vagtbytte</a>
      <a href='' class='headerButton admin'>Administrer rettigheder</a>
      <a href='https://fischer.thomashegelund.dk/' class='headerButton admin'>Kalender</a>
    </div>
    <div id="account">
      <a onclick = "logout()"class="accountButtons" id="logout" href="logout.php"><i class="fa fa-sign-out"></i></a>
      <a class="accountButtons" id="aMenu" href="/menu.php"><i class="fa fa-bars"></i></a>
      <a class="accountButtons" href="/dineOplysninger.php" id="nameLabel"><?php echo htmlspecialchars($_SESSION["name"]); ?></a>
    </div>
  </div>

<div id="administrerBrugereDiv">


<?php

require_once "config.php";
$sql = "SELECT name, email, id FROM `users` WHERE accepteret = 0 ORDER BY name";

if($stmt = mysqli_prepare($link, $sql)){
    // Attempt to execute the prepared statement

    if(mysqli_stmt_execute($stmt)){
        // Store result
      mysqli_stmt_store_result($stmt);
?>
    <div class="brugerTables">
      <h3>Nye brugere</h3>
<?php
        if(mysqli_stmt_num_rows($stmt) >= 1){

            // Bind result variables
            mysqli_stmt_bind_result($stmt, $name, $email, $id);
?>

            <table class='tables' >
              <thead>
                <tr>
                    <th >Navn</th>
                    <th class="hiddenMobile">Email</th>
                    <th class="hiddenMobile"colspan ='2'>Handling</th>
                </tr>
              </thead>
              <tbody>


            <?php
            while (mysqli_stmt_fetch($stmt)) {
              ?>
                <tr>
                  <td onclick='openBruger(this, <?php echo json_encode($email); ?>, 0, <?php echo json_encode($id) ?>)'><?php echo "$name";?></td>
                  <td class="hiddenMobile"><?php echo "$email";?></td>
                  <td class="tActionButton hiddenMobile"onclick='accepterBruger(<?php echo "$id" ?>)';>Accepter</td>
                  <td class="tActionButton hiddenMobile"onclick='fjernbruger(<?php echo "$id" ?>)';>Afvis</td>
                </tr>

          <?php
              }
                ?>
              </tbody>
            </table>

            <?php
            }
          else{
            ?>
            <p>Der er ingen brugere, der venter på, at blive accepteret.</p>
            <?php
          }
          ?>
        </div>
        <?php
        }
      }

      require_once "config.php";
      $sql1 = "SELECT name, email, id FROM `users` WHERE accepteret = 1 AND admin = 0 ORDER BY name";

      if($stmt = mysqli_prepare($link, $sql1)){
          // Attempt to execute the prepared statement

          if(mysqli_stmt_execute($stmt)){
              // Store result
            mysqli_stmt_store_result($stmt);

              if(mysqli_stmt_num_rows($stmt) >= 1){

                  // Bind result variables
                  mysqli_stmt_bind_result($stmt, $name, $email, $id);
?>                <div class="brugerTables">
                    <h3>Brugere</h3>
                  <table class='tables' >
                    <thead>
                      <tr>
                          <th >Navn</th>
                          <th class="hiddenMobile">Email</th>
                          <th class="hiddenMobile"colspan ='2'>Handling</th>
                      </tr>
                    </thead>
                    <tbody>


                  <?php
                  while (mysqli_stmt_fetch($stmt)) {
                    ?>
                      <tr>
                        <td onclick='openBruger(this, <?php echo json_encode($email); ?>, 1, <?php echo json_encode($id) ?>)'><?php echo "$name"; ?> </td>
                        <td class="hiddenMobile"><?php echo "$email"; ?></td>
                        <td class="tActionButton hiddenMobile"onclick='makeAdmin(<?php echo "$id" ?>)';>Gør til administrator</td>
                        <td class="tActionButton hiddenMobile"onclick='fjernbruger(<?php echo "$id" ?>)';>Fjern bruger</td>
                      </tr>

                <?php
                    }
                    ?>
                    </tbody>
                  </table>
                </div>
                    <?php
                  }
              }
            }

            require_once "config.php";
            $sql1 = "SELECT name, email, id FROM `users` WHERE accepteret = 1 AND admin = 1 ORDER BY name";

            if($stmt = mysqli_prepare($link, $sql1)){
                // Attempt to execute the prepared statement

                if(mysqli_stmt_execute($stmt)){
                    // Store result
                  mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) >= 1){

                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $name, $email, $id);
                        ?>
                        <div class="brugerTables">
                          <h3>Administratorer</h3>
                      <table class='tables' >
                          <thead>
                            <tr>
                                <th >Navn</th>
                                <th class="hiddenMobile">Email</th>
                                <th class="hiddenMobile"colspan ='2'>Handling</th>
                            </tr>
                          </thead>
                          <tbody>


                        <?php
                        while (mysqli_stmt_fetch($stmt)) {
                          ?>

                            <tr>
                              <td onclick='openBruger(this, <?php echo json_encode($email); ?>, 2,<?php echo json_encode($id) ?>)'><?php echo"$name"; ?></td>
                              <td class="hiddenMobile"><?php echo "$email"; ?></td>
                              <td class="tActionButton hiddenMobile"onclick='removeAdmin(<?php echo "$id" ?>)';>Fjern som administrator</td>
                              <td class="tActionButton hiddenMobile"onclick='fjernbruger(<?php echo "$id" ?>)';>Fjern bruger</td>
                            </tr>

                            <?php
                          }
                          ?>
                        </tbody>
                      </table>
                      </div>
                      </div>
                      <?php
                        }
                    }
                  }
?>

<div id="popUpDiv">
  <h3 id="popUpDivTitle"></h3>
  <table class="responsiveTables">
    <tbody>
      <tr >
        <td class="rTableHeaders">Navn</td>
        <td id="rTableName"></td>
      </tr>
      <tr>
        <td class="rTableHeaders">Email</td>
        <td id="rTableEmail"></td>
      </tr>

      <tr>
        <td class="rTableHeaders"rowspan="2">Handling</td>
        <td class="tActionButton"id="rButton1"></td>
      </tr>
      <tr>
        <td onclick='fjernbruger(this.value)'class="tActionButton"id="rButton2"></td>
      </tr>
      <tr>
      </tr>
    </tbody>
  </table>
</div>

<script type="text/javascript">
window.onresize = checkWidthPopUp;

function checkWidthPopUp(){
    if (window.innerWidth > 693) {
      document.getElementById('popUpDiv').style = "display:none";
    }
}

function openBruger(ele, email, status, id){
  if (window.innerWidth < 693) {
    let btn1 = document.getElementById("rButton1");
    let btn2 = document.getElementById("rButton2");
    var title = "";
    if (status == 0) {
      title = "Nye brugere";
      btn1.innerHTML = "Accepter";
      btn1.onclick = function(){accepterBruger(id)};

      btn2.innerHTML = "Afvis";

    } else if (status == 1) {
      title = "Brugere";
      btn1.innerHTML = "Gør til administrator";
      btn1.onclick = function(){makeAdmin(id)};

      btn2.innerHTML = "Fjern bruger";
    }
    else if (status == 2) {
      title = "Administratorer";
      btn1.innerHTML = "Fjern som administrator";
      btn1.onclick = function(){removeAdmin(id)};

      btn2.innerHTML = "Fjern bruger";
    }

    btn2.value = id;
    document.getElementById('rTableName').innerHTML = ele.innerHTML;
    document.getElementById('rTableEmail').innerHTML = email;
    document.getElementById('popUpDivTitle').innerHTML = title;
    let popUpDiv = document.getElementById('popUpDiv')
    popUpDiv.style = "display:block";

    event.preventDefault();
    // Using jQuery's animate() method to add smooth page scroll
    // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
    $('html, body').animate({
    scrollTop: $('#popUpDiv').offset().top
  }, 800, function(){

    
    });

  }
}

function fjernbruger(id){
  window.location.href ="http://fischer.thomashegelund.dk/fjernBruger.php?id="+id;
}

function accepterBruger(id){
  window.location.href ="http://fischer.thomashegelund.dk/accepterBruger.php?id="+id;
}
function makeAdmin(id){
  window.location.href ="http://fischer.thomashegelund.dk/makeAdmin.php?id="+id;
}
function removeAdmin(id){
  window.location.href ="http://fischer.thomashegelund.dk/removeAdmin.php?id="+id;
}


</script>
