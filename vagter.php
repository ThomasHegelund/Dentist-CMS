<?php
$dato = $_COOKIE["dato"];
if (!empty($dato)) {
  require_once "config.php";

  $sql = "SELECT id, user_id, afdeling, tid, vagt_accepteret FROM vagter WHERE dag = ? AND month = ? AND year = ? ORDER BY afdeling, tid";
  if($stmt = mysqli_prepare($link, $sql)){

      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "iii", $param_dag, $param_month, $param_year);
      // Set parameters

      $param_dag = (int)explode("/",$dato)[0];
      $param_month = (int)explode("/",$dato)[1];
      $param_year = (int)explode("-",$dato)[1];

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          // Store result

        mysqli_stmt_store_result($stmt);

          if(mysqli_stmt_num_rows($stmt) >= 1){
            mysqli_stmt_bind_result($stmt, $id, $user_id, $afdeling, $tid, $vagt_accepteret);
            $dineVagter = [];
            $andreVagter = [];
            $vagterTilSalgDenneDato = false;
            while (mysqli_stmt_fetch($stmt)) {
              if($user_id == $_SESSION["id"]){
                array_push($dineVagter, $id, $afdeling, $tid);
                $vagterTilSalgDenneDato = true;
              }
              else if($vagt_accepteret==0){
                  array_push($andreVagter,$id,$afdeling,$tid);
                    $vagterTilSalgDenneDato = true;
              }
            }
?>


<?php
            mysqli_stmt_close($stmt);

          }
          if ($vagterTilSalgDenneDato) {
            ?>
            <div id='ydreDiv' value="<?php echo "$dato" ?>">
            <div id='flexDiv'>
              <div class='nedreKolonne' id="nedreKolonneL">
                <div class="tableDiv">
                  <div class="tableHeaderDiv">
                    <h3>Sæt vagt til salg - <?php echo "$dato" ?></h3>
                  </div>
                  <table class="tables inputTable">
                    <thead>
                      <tr>
                          <th style="width: 33%;">Afdeling</th>
                          <th style="width: 17%;">Fra</th>
                          <th style="width: 17%;">Til</th>
                          <th style="width: 33%;">Handling</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><input id='appt3' name='appt'></input></td>
                        <td><input type='time' placeholder="--:--" id='appt1' name='appt'></input></input></td>
                        <td><input type='time' placeholder="--:--"id='appt2' name='appt'></td>
                        <td class="tActionButton"onclick='sætVagtTilSalg()';>Sæt til salg</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </div>
              <div class='nedreKolonne'>
                <div class='tableDiv'>
                  <div class="tableHeaderDiv">
                <h3>Vagter til salg -  <?php echo "$dato" ?></h3>
              </div>
                <table class='tables'>
                  <thead>
                    <tr>
                        <th>Afdeling</th>
                        <th >Tid</th>
                        <th>Handling</th>
                    </tr>
                  </thead>
                  <tbody>
            <?php
            for($x = 0; $x <= count($andreVagter)-1; $x+=3) {
              $id = $andreVagter[$x];
              $afdeling = $andreVagter[$x+1];
              $tid = $andreVagter[$x+2];
              ?>
              <tr>
                <td ><?php echo "$afdeling" ?></td>
                <td ><?php echo "$tid" ?></td>
                <td class="tActionButton"onclick='accepterVagt(<?php echo "$id" ?>)';>Accepter</td>
              </tr>
              <?php
            }

            for($x = 0; $x <= count($dineVagter)-1; $x+=3) {
              $id = $dineVagter[$x];
              $afdeling = $dineVagter[$x+1];
              $tid = $dineVagter[$x+2];
              ?>
              <tr class="specialTRow">
                <td><?php echo "$afdeling" ?></td>
                <td><?php echo "$tid" ?></td>
                <td class="tActionButton"onclick='fjernVagt(<?php echo "$id" ?>)';>Fjern</td>
              </tr>
              <?php
            }
            ?>
            </tbody>
          </table>
          </div>
        </div>
      </div>
            <?php
          } else{
            ?>
            <div id='ydreDiv'>
            <div id='flexDiv'>
              <div class='nedreKolonne' id="nedreKolonneL">
                <div class="tableDiv">
                  <div class="tableHeaderDiv">
                    <h3>Sæt vagt til salg - <?php echo "$dato" ?></h3>
                  </div>
                  <table class="tables inputTable">
                    <thead>
                      <tr>
                          <th style="width: 33%;">Afdeling</th>
                          <th style="width: 17%;">Fra</th>
                          <th style="width: 17%;">Til</th>
                          <th style="width: 33%;">Handling</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><input id='appt3' name='appt'></input></td>
                        <td><input type='time'placeholder="--:--" id='appt1' name='appt'></input></td>
                        <td><input type='time' placeholder="--:--"id='appt2' name='appt'></td>
                        <td class="tActionButton"onclick='sætVagtTilSalg()'>Sæt til salg</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </div>
              <div class='nedreKolonne'>
                <div class='tableDiv'>
                  <div class="tableHeaderDiv">
                <h3>Vagter til salg -  <?php echo "$dato" ?></h3>
                  </div>
                  <table class="tables">
                    <tbody>
                      <tr>
                        <td style="text-align:left;">Der er ingen vagter til salg på den valgte dag.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

            <?php
          }
          ?>


          <?php
          mysqli_close($link);
        }
      }
    }

?>
<script type="text/javascript">
var datefield=document.createElement("input")
datefield.setAttribute("type", "date")

document.querySelectorAll('input[type=number]')
  .forEach(e => e.oninput = () => {
    // Always 2 digits
    if (e.value.length >= 2) e.value = e.value.slice(0, 2);
    // 0 on the left (doesn't work on FF)
    if (e.value.length === 1) e.value = '0' + e.value;
    // Avoiding letters on FF
    if (!e.value) e.value = '00';
  });

</script>

<script>
if (datefield.type!="date"){
  console.log("h")


   //if browser doesn't support input type="date", initialize date picker widget:
   $('#appt1').timeEntry({show24Hours: true, spinnerImage: ''});
   $('#appt2').timeEntry({show24Hours: true, spinnerImage: ''});

}
</script>
