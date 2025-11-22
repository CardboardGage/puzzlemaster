<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PuzzleMaster - Leaderboard Admin Access</title>

  <!-- TODO: more styling -->
  <link rel="stylesheet" href="../css/leaderBoardAdmin.css">
</head>
<body>
  
  <div class="wrapper"><?php
    ini_set('display_errors', '1');
    error_reporting(-1);

    require "../sanitize.php";
    require "../dbConnect.php";

    $runHistoryResult = getFullLeaderboard($pdo);
  
    // if a specific button has been pressed then show this form
    if (sanitizeString(INPUT_POST, 'clicked') == 1) {
  ?>
  <!-- TODO: simple form for adding a new leaderboard entry. should require admin access in the future -->
  <form method="post">
    <label for="">add score section</label>
  </form>
  <?php } 

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