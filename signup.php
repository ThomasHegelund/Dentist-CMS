<?php
// Include config file

require_once "config.php";



// Define variables and initialize with empty values
$email = $name = $name_err = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = $GDPRcheckbox_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "Denne email er allerede benyttet.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Noget gik galt. Prøv igen senere";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    if(empty(trim($_POST["name"]))){
        $name_err = "Skriv dit navn.";
    }  else{
        $name = trim($_POST["name"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Skriv en adgangskode.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Adgangskoden skal mindst være på 6 tegn.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Gentag adgangskoden.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Adgangskoderne er forskellige.";
        }
    }
    if (empty(trim($_POST["GDPRcheckbox"]))) {
      $GDPRcheckbox_err = "Privatlivspolitikken skal accepteres.";
    }

    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($GDPRcheckbox_err)){


        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, password, admin, accepteret) VALUES (?, ?, ?, 0,0)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_password);

            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_name = $name;


            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                ?>
                <script type="text/javascript">
                  alert("Din anmodning om at oprette en bruger er blevet sendt. Administrerne skal nu godkende den.");
                  window.location.href = "https://fischer.thomashegelund.dk/login.php";
                </script>
                <?php
              } else{
                echo "Noget gik galt. Prøv igen senere.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);

    }

    // Close connection
    mysqli_close($link);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Opret bruger</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
    <link rel="stylesheet" href="css.css">
</head>
<body>
  <div class="containerDiv">

    <div class="wrapper">
        <h2>Opret bruger</h2>
        <p>Udfyld denne formular for at ansøge om at blive bruger.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Fulde navn</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Adgangskode</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Bekræft adgangskode</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($GDPRcheckbox_err)) ? 'has-error' : ''; ?>">
                <input type="checkbox" id="GDPRcheckbox"name="GDPRcheckbox" value="1">
                <label for="GDPRcheckbox">Acceptér <a>privatlivspolitikken</a></label>
                <span class="help-block"><?php echo $GDPRcheckbox_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Opret">
                <input type="reset" class="btn btn-default">
            </div>
            <p>Eksisterende bruger? <a href="login.php">Log ind her</a>.</p>
        </form>
    </div>
    </div>
</body>
</html>
