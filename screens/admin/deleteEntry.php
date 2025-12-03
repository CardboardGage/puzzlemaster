<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Run</title>
  
<?php 
ini_set('display_errors', '1');
error_reporting(-1);

require '../../sanitize.php';
require '../../dbConnect.php';
require '../../authenticate.php';

$runData = getRunByID(sanitizeInt(INPUT_GET, 'runID'), $pdo);
$confirm = sanitizeString(INPUT_POST, 'confirm');
$cancel = sanitizeString(INPUT_POST, 'cancel');

if (!isset($runData) || isset($cancel)) {
  header("Location: ../leaderboardAdmin.php");
}

?>

</head>
<body>
  <?php  
  // Pushes changes and redirects back to leaderboardAdmin
  if (isset($confirm)) {
    $runID = trim(sanitizeInt(INPUT_GET, 'runID'));

    deleteRun($runID, $pdo);
    header("Location: ../leaderboardAdmin.php");

  } else {
  ?>
  <form action="" method="post">
    <label>Are you sure?</label><br>
    <label>Delete Run <?= $runData['RunID'] ?>?</label><br>
    <input type="submit" value="Yes" name="confirm">
    <input type="submit" value="No" name="cancel">
  </form><?php
  }
?>
</body>
</html>