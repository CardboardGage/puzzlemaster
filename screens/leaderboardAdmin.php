<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PuzzleMaster - Leaderboard Admin Access</title>

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
    <input type="hidden" name="fromLeaderboard">
    <input type="submit" name="createRun" value="New Entry">
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
      <td class="runID">RunID</td>
      <td class="userID">Username</td>
      <td class="score">Score</td>
      <td class="levelReached">Level Reached</td>
      <td class="timeOf">Time Of Run</td>
      <td class="seed">Seed</td>
      <td class="modeID">ModeID</td>
    </tr><?php 
    // step through the result set one row at a time
    if (isset($pdo)) {
      $runHistoryResult = getFullLeaderboard($pdo);
      while ($entry = $runHistoryResult->fetch()) {
      ?>  
      <tr>
        <td class="links">
          <a href="admin/editEntry.php?runID=<?=$entry['RunID']?>">Edit</a><br>
          <a href="admin/deleteEntry.php?runID=<?=$entry['RunID']?>">Delete</a><br>
        </td>
        <td class="runID"><?= $entry['RunID'] ?></td>
        <td class="userID"><?= ($entry['Username']=="")? "Deleted User": $entry['Username'];?> (UID: <?= $entry['UserID'] ?>)</td>
        <td class="score"><?= $entry['Score'] ?></td>
        <td class="levelReached"><?= $entry['LevelReached'] ?></td>
        <td class="timeOf"><?= $entry['TimeOf'] ?></td>
        <td class="seed"><?= $entry['Seed'] ?></td>
        <td class="modeID"><?= $entry['ModeID'] ?></td>
      </tr><?php
    }
  }
  ?>
  </table>

  </div>

</body>
</html>