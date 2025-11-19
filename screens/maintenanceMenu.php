<?php 
  if (!session_id()) {
    session_start();
  }

  require '../sanitize.php';
  require '../dbconnect.php';

  $method = $_SERVER['REQUEST_METHOD'];

  $_SESSION["loggedIn"] = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Puzzlemaster: Database Maintenance</title>
  <link rel="stylesheet" href="../css/mainMenu.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</head>
<body>
  <div class="wrapper buttons">
    <?php if ($_SESSION['loggedIn'])  { ?>
    <button id="editUserBtn" class="dataBtn">Edit User data</button>
    <div id="userDataEditor" hidden>
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
        <input type="submit" value="Save Changes">
      </form>
      <br>
      <br>
    </div>
    <?php } ?>
    <button id="createRunBtn" class="dataBtn">Generate run data</button><br><br>
    <div id="runDataGenerator" hidden>
      <form ation="" method="post">
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
        <input type="submit" value="Save">
      </form>
      <br>
      <br>
    </div>
    <button id="createModeBtn" class="dataBtn">Create Mode</button>
    <div id="modeEditor" hidden>
      <form ation="" method="post">
        <label for="modeID">Mode ID number</label>
        <input type="text" name="modeID" id="" value="01">
        <br>
        <label for="modeName">Mode</label>
        <input type="text" name="modeName" id="" value="classic">
        <input type="submit" value="Save">
      </form>
      <br>
      <br>
    </div>
    <button id="backBtn" hidden>Cancel</button>
    <button id="returnBtn">Return</button>
  </div>

  <script src="../js/maintenanceMenu.js"></script>

</body>
</html>