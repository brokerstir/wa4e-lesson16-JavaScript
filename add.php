<?php

session_start();

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    $_SESSION["error"] = "Add Profile Cancelled";
    header("Location: index.php");
    return;
}

if ( ! isset($_SESSION['user_id']) ) {
  $_SESSION["error"] = "Not logged in";
  header( 'Location: index.php' ) ;
  return;
} else {
    $user_id = $_SESSION['user_id'];
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Broker Stir | Resume Profiles</title>
<?php

require_once "bootstrap.php";
require_once "pdo.php";
//require_once "pdo_db_live.php";



if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email'])) {

      $stmt = $pdo->prepare('INSERT INTO Profile
          (user_id, first_name, last_name, email, headline, summary)
          VALUES ( :uid, :fn, :ln, :em, :he, :su)');
      $stmt->execute(array(
          ':uid' => $_SESSION['user_id'],
          ':fn' => $_POST['first_name'],
          ':ln' => $_POST['last_name'],
          ':em' => $_POST['email'],
          ':he' => $_POST['headline'],
          ':su' => $_POST['summary'])
      );

        $_SESSION['success'] = "New Profile Added";
        header("Location: index.php");
        return;

  }

?>
</head>
<body>
<div class="container">
<h1>Add Profile</h1>
<?php


if ( isset($_SESSION["error"]) ) {
      echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
      unset($_SESSION["error"]);
  }

?>
<form method="post">

  <p>First Name:<input type="text" name="first_name" size="40"></p>
  <p>Last Name:<input type="text" name="last_name" size="40"></p>
  <p>Email:<input type="text" name="email" size="40"></p>
  <p>Headline:</p>
  <p>
    <input type="text" name="headline" size="70">
  </p>
  <p>Summary:</p>
  <p>
    <textarea name="summary" rows="8" cols="80"></textarea>
  </p>
  <p><input type="submit" name="add" value="Add"/><input type="submit" name="cancel" value="Cancel"></p>

</form>



</div>
</body>
</html>
