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

?>
<!DOCTYPE html>
<html>
<head>

    <!-- home page for autocrud assignment by dr. chuck at wa4e.com -->
    <title>Broker Stir | Resume Profiles</title>
    <?php //require_once "bootstrap.php"; ?>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

    <style>
    table, tr, th, td {
       border: 1px solid black;
    }
    </style>

</head>
<body>

    <div class="container">

        <h1>Resume Profiles</h1>

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


        <?php if ($login == 0) { ?>

          <p>
            <a href="login.php">Please Log In</a>
          </p>

        <?php } else { ?>

          <p>
            <a href="add.php">Add Profile</a>
          </p>

          <p>
            <a href="logout.php">Log Out</a>
          </p>

        <?php } ?>

        <table>

        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>

          <th>Action</th>
        </tr>

          <?php

          $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, email FROM profile");
          while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
            echo "<tr><td>";
            echo(htmlentities($row['first_name']));
            echo("</td><td>");
            echo(htmlentities($row['last_name']));
            echo("</td><td>");
            echo(htmlentities($row['email']));
            echo("</td><td>");
            echo('<a href="view.php?profile_id='.$row['profile_id'].'">View</a> / ');
            echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
            echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
            echo("</td></tr>\n");
          }
          ?>
        </table>

    </div>

</body>
</html>
