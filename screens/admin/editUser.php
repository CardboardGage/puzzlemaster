<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="../../css/mainMenu.css">
  
<?php 
ini_set('display_errors', '1');
error_reporting(-1);

require '../../sanitize.php';
require '../../dbConnect.php';
require '../../authenticate.php';

$userData = getUserByID(sanitizeInt(INPUT_GET, 'userID'), $pdo);
$saveChanges = sanitizeString(INPUT_POST, 'saveChanges');
$cancel = sanitizeString(INPUT_POST, 'cancel');

if (!isset($userData) || isset($cancel)) {
  header("Location: ../usersAdmin.php");
}

?>
<script>
  $(document).ready(() => {
    <?= $userData['Verified'] ?>==1?$("#verifiedBox").prop('checked', true):
      $("#verifiedBox").prop('checked', false);
    <?= $userData['AdminStatus'] ?>==1?$("#adminBox").prop('checked', true):
      $("#adminBox").prop('checked', false);
  });
</script>

</head>
<body>
  <?php  
  // Pushes changes and redirects back to usersAdmin
  if (isset($saveChanges)) {
    $userID = trim(sanitizeInt(INPUT_GET, 'userID'));
    $username = trim(sanitizeString(INPUT_POST, 'username'));
    $email = trim(sanitizeString(INPUT_POST, 'email'));
    $verified = isset($_POST['verified'])?trim(sanitizeInt(INPUT_POST, 'verified')):0;
    $timeCreated = trim(sanitizeString(INPUT_POST, 'timeCreated'));
    $lastLogin = trim(sanitizeString(INPUT_POST, 'lastLogin'));
    $adminStatus = isset($_POST['adminStatus'])?trim(sanitizeInt(INPUT_POST, 'adminStatus')):0;
    
    editUser($userID, $username, $email, $verified, $timeCreated, $lastLogin, $adminStatus, $pdo);
    header("Location: ../usersAdmin.php");

  } else {
  ?>
  <form action="" method="post">
    <label for="userID">UserID number</label>
    <input type="text" name="userID" id="" value="<?= $userData['UserID'] ?>" disabled>
    <br>
    <label for="username">Username</label>
    <input type="text" name="username" id="usernameTxt" value="<?= $userData['Username'] ?>">
    <br>
    <label for="email">Email</label>
    <input type="text" name="email" id="emailTxt" value="<?= $userData['Email'] ?>">
    <br>
    <label for="verified">User is Verified</label>
    <input type="checkbox" name="verified" id="verifiedBox" value="1">
    <br>
    <label for="timeCreated">Account Created</label>
    <input type="text" name="timeCreated" id="timeTxt" value="<?= $userData['TimeCreated'] ?>">
    <br>
    <label for="lastLogin">Last Login</label>
    <input type="text" name="lastLogin" id="lastLoginTxt" value="<?= $userData['LastLogin'] ?>">
    <br>
    <label for="adminStatus">User is Admin</label>
    <input type="checkbox" name="adminStatus" id="adminBox" value="1">
    <br>
    <input type="submit" value="Save Changes" name="saveChanges">
    <input type="submit" value="Cancel" name="cancel">
  </form><?php
  }
?>
</body>
</html>