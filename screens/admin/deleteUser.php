<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete User</title>
  
<?php 
ini_set('display_errors', '1');
error_reporting(-1);

require '../../sanitize.php';
require '../../dbConnect.php';

$userData = getUserByID(sanitizeInt(INPUT_GET, 'userID'), $pdo);
$confirm = sanitizeString(INPUT_POST, 'confirm');
$cancel = sanitizeString(INPUT_POST, 'cancel');

if (!isset($userData) || isset($cancel)) {
  header("Location: ../usersAdmin.php");
}

?>

</head>
<body>
  <?php  
  // Pushes changes and redirects back to usersAdmin
  if (isset($confirm)) {
    $userID = trim(sanitizeInt(INPUT_GET, 'userID'));

    deleteUser($userID, $pdo);
    header("Location: ../usersAdmin.php");

  } else {
  ?>
  <form action="" method="post">
    <label>Are you sure?</label><br>
    <label>WARNING: Deleting a logged in User WILL cause errors.</label><br>
    <label>Delete User <?= $userData['Username'] ?>?</label><br>
    <input type="submit" value="Yes" name="confirm">
    <input type="submit" value="No" name="cancel">
  </form><?php
  }
?>
</body>
</html>