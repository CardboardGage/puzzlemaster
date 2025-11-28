<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PuzzleMaster - User List Admin Access</title>

  <link rel="stylesheet" href="../css/AdminAccess.css">

</head>
<body>
  
  <div class="wrapper"><?php
    ini_set('display_errors', '1');
    error_reporting(-1);

    require "../dbConnect.php";
    if (isset($pdo)) {
    ?> 
  <form action="maintenanceMenu.php" method="get">
    <input type="hidden" name="fromList">
    <input type="submit" name="createUser" value="New Entry">
  </form>
  <br><?php
    }
  ?>
  <form action="maintenanceMenu.php">
    <input type="submit" value="Return">
  </form>
  <table>
    <tr>
      <td class="links">Access</td>
      <td class="userID">UserID</td>
      <td class="username">Username</td>
      <td class="email">Email</td>
      <td class="verified">Verified</td>
      <td class="timeCreated">Time Created</td>
      <td class="lastLogin">Last Login</td>
      <td class="adminStatus">Admin Status</td>
    </tr><?php 
    // step through the result set one row at a time
    if (isset($pdo)) {
      $usersResult = getAllUsers($pdo);
      while ($entry = $usersResult->fetch()) {
      ?>  
      <tr>
        <td class="links">
          <a href="admin/editUser.php?userID=<?=$entry['UserID']?>">Edit</a><br>
          <a href="admin/deleteUser.php?userID=<?=$entry['UserID']?>">Delete</a><br>
        </td>
        <td class="userID"><?= $entry['UserID'] ?></td>
        <td class="username"><?= $entry['Username'] ?></td>
        <td class="email"><?= $entry['Email'] ?></td>
        <td class="verified"><?= (($entry['Verified'] == 1) ? "Verified":"Not Verified"); ?></td>
        <td class="timeCreated"><?= $entry['TimeCreated'] ?></td>
        <td class="lastLogin"><?= $entry['LastLogin'] ?></td>
        <td class="adminStatus"><?= (($entry['AdminStatus'] == 1) ? "Admin":"User"); ?></td>
      </tr><?php
    }
  }
  ?>
  </table>

  </div>

</body>
</html>