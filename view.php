<?php
session_start();

require_once "pdo.php";
//require_once "pdo_db_live.php";

if ( ! isset($_SESSION['user_id']) ) {
    $login = 0;
} else {
    $login = 1;
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: Logout.php');
    return;
}

// Guardian: Make sure that profile_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing Parameter";
  header('Location: index.php');
  return;
} else {
  $profile_id = $_GET['profile_id'];
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Robert Risk | Resume Profiles</title>
<?php

require_once "bootstrap.php";

?>

<style>
table, tr, th, td {
   border: 1px solid black;
}
</style>

</head>
<body>
<div class="container">
<h1>Resume Profile View</h1>

<?php
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}

if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>

  <?php

  $stmt = $pdo->query("SELECT profile_id, first_name, last_name, email, headline, summary FROM profile Where profile_id = $profile_id");
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<h3>";
    echo(htmlentities($row['first_name']) . ' ' . htmlentities($row['last_name']));
    echo("</h3>");

    echo "<h3>";
    echo(htmlentities($row['email']));
    echo("</h3>");

    echo "<h3>";
    echo(htmlentities($row['headline']));
    echo("</h3>");

    echo "<p>";
    echo(htmlentities($row['summary']));
    echo("</p>");
  }

  echo "<p>";
  echo('<a href="edit.php?profile_id='.$profile_id.'">Edit</a> ');
  echo("</p>");
  ?>


  <p><a href="add.php">Add New</a></p>
  <p><a href="index.php">Home Page</a></p>
  <p><a href="logout.php">Logout</a></p>

</div>
</body>
</html>
