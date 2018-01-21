<?php
require_once "pdo.php";
//require_once "pdo_db_live.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['profile']) ) {
?>
  <script>
      alert('Are you sure you want to delete this profile? If yes, click OK.');
  </script>
<?php
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile']));
    $_SESSION['success'] = 'Record Deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_SESSION['user_id']) ) {
  $_SESSION['error'] = "Not logged in";
  header('Location: index.php');
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

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Record Not Found';
    header( 'Location: view.php' ) ;
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Broker Stir | Tracking Autos</title>
<?php

require_once "bootstrap.php";

?>
</head>
<body>
<div class="container">

<p>Confirm: Delete profile for <?= htmlentities($row['email']) ?></p>

<form onsubmit="return confirm('Are you sure you want to delete this profile? If yes, click OK.');" method="post">
<input type="hidden" name="profile" value="<?= $row['profile_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="view.php">Cancel</a>
</form>

</div>
</body>



</html>
