  <?php
  ini_set('display_errors', '1');
  error_reporting(-1);

  if (!isset($pdo)) {
    require "dbConnect.php";
  }
  
  ?>
  <div id="leaderboard">
  <table>
    <tr>
      <td class="userID"><b>User</b></td>
      <td class="scoreLabel"><b>Score</b></td>
    </tr><?php 
    if (isset($pdo)) {
      $runHistoryResult = getFullLeaderboard($pdo);
      while ($entry = $runHistoryResult->fetch()) {
    ?>  
    <tr>
      <td class="username"><?= ($entry['Username']=="")? "Deleted User": $entry['Username'];?></td>
      <td class="score"><?= $entry['Score'] ?></td>
    </tr><?php
    }
  }
  ?>
  </table>
  </div>
  <?php
  
