<?php // Do not put any HTML above this line
session_start();

require_once "bootstrap.php";
require_once "pdo.php";
//require_once "pdo_db_live.php";

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
//$stored_hash = 'a8609e8d62c043243c4e201cbb342862';  // Pw is meow123
//$secret = 'php123';

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {

    unset($_SESSION["email"]);  // Logout current user

    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {

        $_SESSION["error"] = "Email and password are required";
        header( 'Location: login.php' ) ;
        return;

    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $_SESSION["error"] = "Email must have an at-sign (@)";
        header( 'Location: login.php' ) ;
        return;

    } else {

        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
        WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( $row !== false ) {

            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;

          } else {

            error_log("Login fail ".$_POST['email']);
            $_SESSION["error"] = "Login Failed";
            header( 'Location: login.php' ) ;
            return;

        }
    }
} // end if ( isset($_POST['email']) && isset($_POST['pass']) )

// Fall through into the View
?>
<!DOCTYPE html>
<html>

<head>
<?php require_once "bootstrap.php"; ?>
<title>Robert Risk | Resume Profiles</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php

if ( isset($_SESSION["error"]) ) {
      echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
      unset($_SESSION["error"]);
  }
?>
<form method="POST">

User Name <input type="text" name="email" id="id_1722"><br/>
Password <input type="password" name="pass" id="id_1723"><br/>

<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">

</form>

</div>

<script>

function doValidate() {
    console.log('Validating...');
    try {
        un = document.getElementById('id_1722').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating pw="+pw);
        console.log("Validating un="+un);
        if (un == null || un == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}

</script>


</body>

</html>
