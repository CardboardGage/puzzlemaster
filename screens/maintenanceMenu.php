<?php 
  if (!session_id()) {
    session_start();
  }

  require '../sanitize.php';
  require '../dbconnect.php';

  $method = $_SERVER['REQUEST_METHOD'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Puzzlemaster: Database Maintenance</title>
</head>
<body>
  <div class="wrapper">
    <?php if ($_SESSION['loggedIn'])  { ?>
    <div id="userDataEditor">
    <form action="" method="post">
      <label for="userID">UserID</label>
      <br>
      <label for="email">Email</label>
      <input type="text" placeholder="you@email.com" name="email" required maxlength="50">
      <br>
      <label for="username">Username</label>
      <input type="text" placeholder="username" name="username" required maxlength="24"> 
      <br>    
      <label for="verified">Email Verified?</label>
      <input type="checkbox" name="tutorialFlag" id="">
      <br>
      <label for="timeCreated">Account Created: </label>
      <input type="text" name="timeCreated" id="" value="09/12/2000" disabled>
      <br>
      <label for="lastLogin">Last Login: </label>
      <input type="text" name="lastLogin" id="" value="10/12/0000" disabled>
      <br>
      <label for="tutorialFlag">Tutorial Complete: </label>
      <input type="checkbox" name="tutorialFlag" id="">
    </form>
    </div>
    <br>
    <br>
    <?php } ?>
    <div id="runDataGenerator">
      <label for="runID">RunID number</label>
      <input type="text" name="runID" id="" value="123" disabled>
      <br>
      <label for="userID">UserID number</label>
      <input type="text" name="userID" id="" value="004" disabled>
      <br>
      <label for="score">Score</label>
      <input type="text" name="score" id="scoreTxt" value="0">
      <br>
      <label for="levelReached">Level Reached</label>
      <input type="text" name="levelReached" id="" value="0">
      <br>
      <label for="timeOf">Time of Run</label>
      <input type="text" name="timeOf" id="" value="0">
      <br>
      <label for="mode">Mode</label>
      <input type="text" name="mode" id="" value="0">
      <br>
      <br>
    </div>
    <div id="modeEditor">
      <label for="modeID">Mode ID number</label>
      <input type="text" name="modeID" id="" value="01">
      <br>
      <label for="modeName">Mode</label>
      <input type="text" name="modeName" id="" value="classic">
    </div>
  </div>
</body>
</html>