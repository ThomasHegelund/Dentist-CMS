<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$_SESSION['url'] = "dineOplysninger.php";
?>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
  echo $_POST["adgangskode"];

  if(empty(trim($_POST["name"]))){
    $name_err = "Feltet skal udfyldes.";
  }elseif(empty(trim($_POST["email"]))){
    $email_err = "Feltet skal udfyldes.";
  }
  elseif(empty(trim($_POST["password"]))){
    $email_err = "Feltet skal udfyldes.";
  }
  elseif ($_POST["name"] != $_SESSION["name"] || $_POST["email"] != $_SESSION["email"]){
    // Include config file
    require_once "config.php";


    if ($_POST["password"] != "********") {
      // Prepare an update statement
      $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";

      if($stmt = mysqli_prepare($link, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_email, $param_password, $param_id);

          // Set parameters
          $param_name = $_POST["name"];
          $param_email = $_POST["email"];
          $param_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
          $param_id = $_SESSION["id"];

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              // Password updated successfully. Destroy the session, and redirect to login page
              $_SESSION["name"] = $_POST["name"];
              $_SESSION["email"] = $_POST["email"];

              header("location: https://fischer.thomashegelund.dk/");
              exit();
          } else{
              echo "Oops! Noget gik galt. Prøv igen seneres.";
          }

          // Close statement
          mysqli_stmt_close($stmt);
      }
    }
      else{
        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_name, $param_email, $param_id);

            // Set parameters
            $param_name = $_POST["name"];
            $param_email = $_POST["email"];
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                $_SESSION["name"] = $_POST["name"];
                $_SESSION["email"] = $_POST["email"];

                header("location: https://fischer.thomashegelund.dk/");
                exit();
            } else{
                echo "Oops! Noget gik galt. Prøv igen seneres.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
      }
    }
  else {
    header("location: https://fischer.thomashegelund.dk/");
  }
}



?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Dine oplysninger</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


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
        <a href='https://fischer.thomashegelund.dk/' class='headerButton admin'>Kalender</a>
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
      <a class="accountButtons" href="/dineOplysninger.php"><i class="fa fa-cog"></i></a>
      <a class="accountButtons" href="/dineOplysninger.php" id="nameLabel"><?php echo htmlspecialchars($_SESSION["name"]); ?></a>
    </div>
  </div>
    <?php
  }
  ?>

<div id="dineOplysningerDiv">
  <h3>Dine oplysninger</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <table class="tables" id="dineOplysningerRTable">
        <thead>
          <th>Navn</th>
          <th>Email</th>
          <th>Adgangskode</th>
        </thead>
        <tbody>
          <td>
            <div class="form-guppe <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
              <input type="text" id="name" name="name" value="<?php echo $_SESSION["name"];?>">
            </div>
          </td>

          <td>
            <div class="form-guppe <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
              <input type="email" id="email" name="email" value="<?php echo $_SESSION["email"];?>">
              <span class="help-block"><?php echo $email_err; ?></span>
            </div>
          </td>
          <td>
            <div class="form-guppe <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
              <input type="password" id="password" name="password" value="********">
              <span class="help-block"><?php echo $password_err; ?></span>
            </div>
          </td>
          <?php
          if (!empty($name_err)|| !empty($email_err)|| !empty($password_err)) {
            ?>
            <tr>
              <td><span class="help-block"><?php echo $name_err; ?></span></td>
            </tr>
            <tr>
              <td><span class="help-block"><?php echo $email_err; ?></span></td>
            </tr>
            <tr>
              <td><span class="help-block"><?php echo $password_err; ?></span></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>

      <table  id="responsiveDineOplysninger">
        <tbody>
          <tr >
            <td class="rTableHeaders">Dato</td>
            <td id="rTableNavn">
              <div class="form-guppe <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
              <input type="text" id="name" name="name" value="<?php echo $_SESSION["name"];?>">
            </div>
          </td>
          </tr>
          <tr>
            <td class="rTableHeaders">Tid</td>
            <td id="rTableEmail">
              <div class="form-guppe <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <input type="email" id="email" name="email" value="<?php echo $_SESSION["email"];?>">
                <span class="help-block"><?php echo $email_err; ?></span>
              </div>
            </td>
          </tr>
          <tr >
            <td class="rTableHeaders">Afdeling</td>
            <td id="rTableAdgangskode">
              <div class="form-guppe <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" id="password" name="password" value="********">
                <span class="help-block"><?php echo $password_err; ?></span>
              </div>
            </td>
          </tr>

          <tr>
            <td class="rTableHeaders"rowspan="3">Handling</td>
            <td class="dineOplysningerTableButton"><input type="submit" value="Gem"></td>
          </tr>
          <tr>
            <td class="dineOplysningerTableButton">
              <button type="button" onclick='Annuller()'>Annuller</button>
            </td>
          </tr>
          <tr>
            <td class="dineOplysningerTableButton">
              <button type="button" name="button" onclick='Sure()'>Slet Bruger</button>
              </td>
          </tr>
        </tbody>
      </table>

      <br>
      <input class="dineOplysningerButton responsiveDineOplysningerButton"type="submit" value="Gem">
      <button class="dineOplysningerButton responsiveDineOplysningerButton" href="https://fischer.thomashegelund.dk/">Annuller</button>
      <button class="dineOplysningerButton responsiveDineOplysningerButton" onclick="Sure()">Slet bruger</button>
      <button class="dineOplysningerButton" style="float:right;"onclick="">GDPR</button>
    </form>
</div>


  <script type="text/javascript">
  function Sure() {
    if (confirm("Er du sikker på, at du vil slette din bruger?")) {
      window.location.href = "https://fischer.thomashegelund.dk/fjernBruger.php";
    }
  }

  function Annuller(){
    window.location.href = "https://fischer.thomashegelund.dk/";
  }
  </script>
