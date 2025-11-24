<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Run</title>
  
<?php 
ini_set('display_errors', '1');
error_reporting(-1);

require '../../sanitize.php';
require '../../dbConnect.php';

$runData = getRunByID(sanitizeInt(INPUT_GET, 'runID'), $pdo);
$saveChanges = sanitizeString(INPUT_POST, 'saveChanges');
$cancel = sanitizeString(INPUT_POST, 'cancel');

if (!isset($runData) || isset($cancel)) {
  header("Location: ../leaderboardAdmin.php");
}


?>
<script>
  $(document).ready(() => {
  $("#modeSelect").prop("selectedIndex", <?= $runData['ModeID'] ?>); 
  });
</script>

</head>
<body>
  <?php  
  // Pushes changes and redirects back to leaderboardAdmin
  if (isset($saveChanges)) {
    $runID = trim(sanitizeInt(INPUT_GET, 'runID'));
    $score = trim(sanitizeInt(INPUT_POST, 'score'));
    $level = trim(sanitizeInt(INPUT_POST, 'levelReached'));
    $timeOf = trim(sanitizeString(INPUT_POST, 'timeOf'));
    $seed = trim(sanitizeInt(INPUT_POST, 'seed'));
    $mode = trim(sanitizeInt(INPUT_POST, 'mode'));

    editRun($runID, $score, $level, $timeOf, $seed, $mode, $pdo);
    header("Location: ../leaderboardAdmin.php");

  } else {
  ?>
  <form action="" method="post">
    <label for="runID">RunID number</label>
    <input type="text" name="runID" id="" value="<?= $runData['RunID'] ?>" disabled>
    <br>
    <label for="userID">UserID number</label>
    <input type="text" name="userID" id="" value="<?= $runData['UserID'] ?>" disabled>
    <br>
    <label for="score">Score</label>
    <input type="text" name="score" id="scoreTxt" value="<?= $runData['Score'] ?>">
    <br>
    <label for="timeOf">Time Of</label>
    <input type="text" name="timeOf" id="timeTxt" value="<?= $runData['TimeOf'] ?>">
    <br>
    <label for="levelReached">Level Reached</label>
    <input type="text" name="levelReached" id="" value="<?= $runData['LevelReached'] ?>">
    <br>
    <label for="seed">Seed</label>
    <input type="text" name="seed" value="<?= $runData['Seed'] ?>">
    <br>
    <label for="mode">Mode</label>
    <select name="mode" id="modeSelect"><?php 
      $modeList = getModes($pdo);
      while ($mode = $modeList->fetch()) {
        ?>
        <option value="<?= $mode['ModeID'] ?>"><?= $mode["Mode"] ?>(<?= $mode['ModeID'] ?>)</option><?php
      }
      ?></select><br>
    <input type="submit" value="Save Changes" name="saveChanges">
    <input type="submit" value="Cancel" name="cancel">
  </form><?php
  }
?>
</body>
</html>