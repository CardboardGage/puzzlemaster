<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PuzzleMaster - Leaderboard Admin Access</title>

  <!-- TODO: more styling -->
  <link rel="stylesheet" href="../css/leaderBoardAdmin.css">

  <?php 
  ?>

</head>
<body>
  
  <div class="wrapper"><?php
    ini_set('display_errors', '1');
    error_reporting(-1);
    date_default_timezone_set("America/Chicago");
    (String)$currentTime = date("Y-m-d H:i:s");
    require "../sanitize.php";
    require "../dbConnect.php";
    
    $thisPage = sanitizeString(INPUT_SERVER, 'PHP_SELF');
    $method = $_SERVER['REQUEST_METHOD'];
  
    // if a specific button has been pressed then show this form
  if (sanitizeString(INPUT_GET, 'clicked') == 1) {
  ?>
  <!-- TODO: simple form for adding a new leaderboard entry. should require admin access in the future -->
  <form method="post">
    <label for="runID">RunID</label>
    <input type="text" name="runID" value=<?= (int)getNextRunID($pdo)+1 ?> required maxlength="4" disabled><br>
    <label for="userID">UserID</label>
    <input type="text" name="userID" value="1" required maxlength="4"><br>
    <label for="score">Score</label>
    <input type="text" name="score" value="10000" required maxlength="8"><br>
    <label for="levelReached">Level Reached</label>
    <input type="text" name="levelReached" value="5" required maxlength="2"><br>
    <label for="timeOf">Time of Run</label>
    <input type="text" name="timeOf" value="<?php echo($currentTime) ?>" required maxlength="24"><br>
    <label for="seed">Seed</label>
    <input type="text" name="seed" value="16384" required maxlength="12"><br>
    <label for="mode">Mode</label>
    <select name="mode"><?php 
    
    $modeList = getModes($pdo);

    while ($mode = $modeList->fetch()) {
      ?>
      <option value="<?= $mode['ModeID'] ?>"><?= $mode["Mode"] ?></option><?php
    }
    ?></select>
    <br>
    <input type="submit" value="Add Entry" name="addEntry">
  </form>
  <?php 
  } else {
    ?> 
  <a href="<?= $thisPage ?>?clicked=1">Add new entry</a><br><?php
  }

  // $addEntrySubmit = sanitizeString(INPUT_POST, "addEntry");
  // echo($addEntrySubmit);
  // if (isset($addEntrySubmit)) {
  if ($method == 'POST') {
    echo ("isset passed");

    $runID = trim(sanitizeInt(INPUT_POST, 'runID'));
    $userID = trim(sanitizeInt(INPUT_POST, 'userID'));
    $score = trim(sanitizeInt(INPUT_POST, 'score'));
    $level = trim(sanitizeInt(INPUT_POST, 'levelReached'));
    $timeOf = trim(sanitizeString(INPUT_POST, 'timeOf'));
    $seed = trim(sanitizeInt(INPUT_POST, 'seed'));
    $mode = trim(sanitizeInt(INPUT_POST, 'mode'));

    echo ($timeOf);

    if (!$runID || !$userID || !$score || !$level || !$timeOf ||
     !$seed || !$mode) {
      echo ("something is not set");
      // header('Location: leaderBoardAdmin.php');
      // exit;
     }

    // addNewRun($runID, $userID, $score, $level, $timeOf, $seed, $mode, $pdo);
    // header('Location: leaderBoardAdmin.php');
    // exit;
  }

  $runHistoryResult = getFullLeaderboard($pdo);
  
  ?>
  <table><?php 
    // step through the result set one row at a time
    while ($entry = $runHistoryResult->fetch()) {
      ?>  
      <!-- class names may need to be changed and formatting may be a little clunky for now -->
      <tr>
        <td class="links">
          <!-- TODO: add link to an edit page with parameters for the RunID(?) -->
          <a href="">Edit</a><br>
          <a href="">Delete</a><br>
        </td>
        <td class="runID"><?= $entry['RunID'] ?></td>
        <td class="userID"><?= $entry['Username'] ?> (UID: <?= $entry['UserID'] ?>)</td>
        <td class="score"><?= $entry['Score'] ?></td>
        <td class="levelReached"><?= $entry['LevelReached'] ?></td>
        <td class="timeOf"><?= $entry['TimeOf'] ?></td>
        <td class="seed"><?= $entry['Seed'] ?></td>
        <td class="modeID"><?= $entry['ModeID'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>

  </div>

</body>
</html>