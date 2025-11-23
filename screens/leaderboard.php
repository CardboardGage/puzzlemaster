  <?php
  ini_set('display_errors', '1');
  error_reporting(-1);

  require "dbConnect.php";
  $runHistoryResult = getFullLeaderboard($pdo);

  ?>
  <div id="leaderboard">
  <table>
    <tr>
      <td class="userID">User</td>
      <td class="score">Score</td>
    </tr><?php 
    while ($entry = $runHistoryResult->fetch()) {
      ?>  
      <tr>
        <td class="userID"><?= $entry['Username'] ?></td>
        <td class="score"><?= $entry['Score'] ?></td>
      </tr><?php
    }
    ?>
  </table>
  </div>
