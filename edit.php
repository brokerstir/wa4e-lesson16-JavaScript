<?php
require_once "pdo.php";
//require_once "pdo_db_live.php";
session_start();


if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) ) {

       if ($_POST['user_id'] != $_SESSION['user_id']) {
         $_SESSION['error'] = "Not logged in user";
         header('Location: index.php');
         return;}

    // Data validation should go here (see add.php)
    $sql = "UPDATE profile SET first_name = :first_name,
            last_name = :last_name, email = :email, headline = :headline, summary = :summary
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary'],
        ':profile_id' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profile Updated';
    header( 'Location: index.php' ) ;
    return;
}

/// Guardian: first_name sure that user_id is in session
if ( ! isset($_SESSION['user_id']) ) {
  $_SESSION['error'] = "Not logged in";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Sorry, Something Went Wrong';
    header( 'Location: index.php' ) ;
    return;
}

$n = htmlentities($row['first_name']);
$e = htmlentities($row['last_name']);
$p = htmlentities($row['email']);
$m = htmlentities($row['headline']);
$s = htmlentities($row['summary']);
$user_id = $row['user_id'];
$profile_id = $row['profile_id'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Broker Stir | Resume Profiles</title>
<?php

require_once "bootstrap.php";

?>
</head>
<body>
<p>Edit Profile</p>
<form method="post">
<p>First name:
<input type="text" name="first_name" value="<?= $n ?>"></p>
<p>Last Name:
<input type="text" name="last_name" value="<?= $e ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $p ?>"></p>
<p>Headline:
<input type="text" name="headline" value="<?= $m ?>"></p>
<p>Summary:</p>
<textarea rows="4" cols="50" name="summary"><?= $s ?></textarea>
<input type="hidden" name="user_id" value="<?= $user_id ?>">
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<p><input type="submit" value="Update"/>
<a href="index.php">Cancel</a></p>
</form>

</body>
</html>
