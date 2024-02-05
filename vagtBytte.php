<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
elseif (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== 1) {
  header("location: https://fischer.thomashegelund.dk/");
  exit;
}
$_SESSION['url'] = "vagtBytte.php";

?>
<head>
    <meta charset="UTF-8">
    <title>Administrer vagtbytte</title>

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
      <a href='' class='headerButton admin'>Administrer vagtbytte</a>
      <a href='/administrerBrugere.php' class='headerButton admin'>Administrer rettigheder</a>
      <a href='https://fischer.thomashegelund.dk/' class='headerButton admin'>Kalender</a>
    </div>
    <div id="account">
      <a onclick = "logout()"class="accountButtons" id="logout" href="logout.php"><i class="fa fa-sign-out"></i></a>
      <a class="accountButtons" id="aMenu" href="/menu.php"><i class="fa fa-bars"></i></a>
      <a class="accountButtons" href="/dineOplysninger.php" id="nameLabel"><?php echo htmlspecialchars($_SESSION["name"]); ?></a>
    </div>
  </div>

<div id="VagtBytteDiv">
  <h3>Vagter til godkendelse</h3>
<?php


require_once "config.php";
$sql = "SELECT vagter.dag,vagter.month,vagter.year, vagter.tid, vagter.afdeling, users1.name, users2.name, vagter_accepterede.id FROM vagter vagter, users users1, users users2, vagter_accepterede vagter_accepterede WHERE vagter.id = vagter_accepterede.vagt_id AND users1.id = vagter_accepterede.user1_id AND users2.id = vagter_accepterede.user2_id ORDER BY vagter.year,vagter.month,vagter.dag";

if($stmt = mysqli_prepare($link, $sql)){
    // Attempt to execute the prepared statement

    if(mysqli_stmt_execute($stmt)){
        // Store result
      mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) >= 1){

            // Bind result variables
            mysqli_stmt_bind_result($stmt, $dag, $month, $year, $tid, $afdeling, $name1, $name2, $vagter_accepterede_id);

            ?>

            <table id="vagtBytteTable"class='tables'>
              <thead>
                <tr>
                    <th >Dato</th>
                    <th >Tid</th>
                    <th class="">Afdeling</th>
                    <th class="vagterHiddenMobil">Afsender</th>
                    <th class="vagterHiddenMobil">Modtager</th>
                    <th class="vagterHiddenMobil"colspan ='2'>Handling</th>
                </tr>
              </thead>
              <tbody>



            <?php while (mysqli_stmt_fetch($stmt)) {
              $dato = "$dag/$month-$year";
                ?>
                <tr onclick='openVagt(<?php echo json_encode($dato); ?>, <?php echo json_encode($tid); ?>, <?php echo json_encode($afdeling); ?>, <?php echo json_encode($name1); ?>, <?php echo json_encode($name2); ?>, <?php echo json_encode($vagter_accepterede_id) ?>)'>
                  <td ><?php echo "$dag/$month-$year"; ?></td>
                  <td ><?php echo "$tid"; ?></td>
                  <td class=""><?php echo "$afdeling"; ?></td>
                  <td class="vagterHiddenMobil"><?php echo "$name1"; ?></td>
                  <td class="vagterHiddenMobil"><?php echo "$name2"; ?></td>
                  <td class="tActionButton vagterHiddenMobil"onclick='accepterVagtBytte(<?php echo "$vagter_accepterede_id" ?>)'>Accepter</td>
                  <td class="tActionButton vagterHiddenMobil"onclick='afvisVagtBytte(<?php echo "$vagter_accepterede_id" ?>)'>Afvis</td>
                </tr>
              <?php
            }
              ?>

              </tbody></table>
            <?php
          }
          else{
            ?>
            <p>Der er ingen vagter, der venter på, at blive accepteret.</p>
          <?php
          }
          ?>
          </div>

          <div id="popUpDiv">
            <h3 id="popUpDivTitle">Vagt</h3>
            <table class="responsiveTables">
              <tbody>
                <tr >
                  <td class="rTableHeaders">Dato</td>
                  <td id="rTableDato"></td>
                </tr>
                <tr>
                  <td class="rTableHeaders">Tid</td>
                  <td id="rTableTid"></td>
                </tr>
                <tr >
                  <td class="rTableHeaders">Afdeling</td>
                  <td id="rTableAfdeling"></td>
                </tr>
                <tr >
                  <td class="rTableHeaders">Afsender</td>
                  <td id="rTableAfsender"></td>
                </tr>
                <tr>
                  <td class="rTableHeaders">Modtager</td>
                  <td id="rTableModtager"></td>
                </tr>

                <tr>
                  <td class="rTableHeaders"rowspan="2">Handling</td>
                  <td onclick="accepterVagtByttethis.value)"class="tActionButton"id="rButton1">Accepter</td>
                </tr>
                <tr>
                  <td onclick='afvisVagtBytte(this.value)'class="tActionButton"id="rButton2">Afvis</td>
                </tr>
                <tr>
                </tr>
              </tbody>
            </table>
          </div>
          <?
        }
      }

?>
<script type="text/javascript">
function openVagt(dato, tid, afdeling, navn1, navn2, vagter_accepterede_id){
  if (window.innerWidth < 693) {


    document.getElementById('rTableDato').innerHTML = dato;
    document.getElementById('rTableTid').innerHTML = tid;

    document.getElementById('rTableAfdeling').innerHTML = afdeling;

    document.getElementById('rTableAfsender').innerHTML = navn1;
    document.getElementById('rTableModtager').innerHTML = navn2;

    document.getElementById("rButton1").value = vagter_accepterede_id;
    document.getElementById("rButton2").value = vagter_accepterede_id;
    let popUpDiv = document.getElementById('popUpDiv');
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

function afvisVagtBytte(id){
  window.location.href ="http://fischer.thomashegelund.dk/afvisVagtBytte.php?accepteret_vagt_id="+id;
}

function accepterVagtBytte(id){
  window.location.href ="http://fischer.thomashegelund.dk/accepterVagtBytte.php?accepteret_vagt_id="+id;
}
</script>
