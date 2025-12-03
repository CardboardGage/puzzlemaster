  <?php
  ini_set('display_errors', '1');
  error_reporting(-1);

  if (!isset($pdo)) {
    require "dbConnect.php";
  }
  
  $method = $_SERVER['REQUEST_METHOD'];
  
  ?>

  <script>
    $(document).ready(() => {
      $("#sortNormal").on("click", ()=>{
        window.location.href = "index.php";
      });
      $("#sortSeeded").on("click", ()=>{
        window.location.href = "index.php?seeded";
      });
      $("#sortLevel").on("click", ()=>{
        window.location.href = "index.php?highLevel";
      });
    });
  </script>

  <div class="leaderboard">
  <table>
    <?php 
    if ($method == "GET" && isset($_GET["seeded"])) {?>
    <button class="sortBtn" id="sortLevel">High Score (Seeded)</button>
    <tr>
      <td class="userID"><b>User</b></td>
      <td class="score"><b>Score</b></td>
    </tr><?php 
    if (isset($pdo)) {
      $runHistoryResult = getLimitedLeaderboard("seeded", $pdo);
      while ($entry = $runHistoryResult->fetch()) {
    ?>  
    <tr>
      <td class="username"><?php
        if ($entry['UserID']==0) {
          echo("Guest");
        } else {
          echo(($entry['Username']=="")? "Deleted User": $entry['Username']); }
          ?></td>
      <td class="score"><?= $entry['Score'] ?></td>
    </tr>
    <?php
        }
      }
    } else if ($method == "GET" && isset($_GET["highLevel"])) {?>
    <button class="sortBtn" id="sortNormal">Highest Level</button>
    <tr>
      <td class="userID"><b>User</b></td>
      <td class="score"><b>Score</b></td>
      <td class="level"><b>Level</b></td>
    </tr><?php 
    if (isset($pdo)) {
      $runHistoryResult = getLimitedLeaderboard("highLevel", $pdo);
      while ($entry = $runHistoryResult->fetch()) {
    ?>  
    <tr>
      <td class="username"><?php
        if ($entry['UserID']==0) {
          echo("Guest");
        } else {
          echo(($entry['Username']=="")? "Deleted User": $entry['Username']); }
          ?></td>
      <td class="score"><?= $entry['Score'] ?></td>
      <td class="level"><?= $entry['LevelReached'] ?></td>
    </tr>
    <?php
        }
      }
    } else {?>
    <button class="sortBtn" id="sortSeeded">High Score</button>
    <tr>
      <td class="userID"><b>User</b></td>
      <td class="score"><b>Score</b></td>
    </tr><?php 
    if (isset($pdo)) {
      $runHistoryResult = getLimitedLeaderboard("normal", $pdo);
      while ($entry = $runHistoryResult->fetch()) {
    ?>  
    <tr>
      <td class="username"><?php
        if ($entry['UserID']==0) {
          echo("Guest");
        } else {
          echo(($entry['Username']=="")? "Deleted User": $entry['Username']); }
          ?></td>
      <td class="score"><?= $entry['Score'] ?></td>
    </tr><?php
    }
  }
}
  ?>
  </table>
  </div>
  <?php
  
