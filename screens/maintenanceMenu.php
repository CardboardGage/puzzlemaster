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
  <link rel="stylesheet" href="../css/maintenanceMenu.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</head>
<body>
  <div class="wrapper buttons">
    <?php if ($_SESSION['loggedIn'] && $method == "GET")  { ?>

      <!-- main maintenance menu -->
      <?php if (!isset($_GET["editUser"]) && !isset($_GET["createRun"]) && !isset($_GET["createMode"]) && !isset($_GET["userSubmit"])) { ?> 
        <form action="" method="get">
          <input type="submit" id="editUserBtn" class="databBtn" value="Edit User Data" name="editUser">
        </form>
        <form action="" method="get">
          <input type="submit" id="createRunBtn" class="dataBtn" value="Create Run Data" name="createRun">
        </form>
        <form action="leaderboardAdmin.php" method="post">
          <input type="submit" id="leaderboardBtn" class="dataBtn" value="View Leaderboard" name="leaderboard">
        </form>
        <form action="" method="get">
          <input type="submit" id="createModeBtn" class="dataBtn" value="Create Mode" name="createMode">
        </form>
        <form action="../index.php">
          <input type="submit" id="returnBtn" class="dataBtn" value="Return">
        </form>
      <?php  } ?> 
    
    <!-- selecting user to edit -->
    <?php if ($method == "GET" && isset($_GET["editUser"])) { ?>
      <form action="" method="get">
        <label for="username"> Username to edit: </label>
        <input type="text" name="username">
        <input type="submit" class="dataBtn" value="Submit" name="userSubmit">
      </form>
    <?php } ?> 

    <!-- editing user data -->
    <?php if ($method == "GET" && isset($_GET["userSubmit"])) {
      $username = trim(sanitizeString(INPUT_GET, 'username'));

      try {
        $data = getUserDataByUsername($username, $pdo);
      } catch (PDOException $e) {
        echo $e->getMessage();
      } finally {
    ?> 
    <div id="userDataEditor">
      <form action="" method="post">
        <label for="userID">UserID</label>
        <input type="text" name="userID" readonly value="<?=$data["UserID"]?>">
        <br>
        <label for="email">Email</label>
        <input type="text" placeholder="you@email.com" name="email" required maxlength="50" value="<?=$data["Email"]?>">
        <br>
        <label for="username">Username</label>
        <input type="text" placeholder="username" name="username" required maxlength="24" value="<?=$data["Username"]?>"> 
        <br>    
        <label for="verified">Email Verified?</label>
        <input type="checkbox" name="verified" value="true" id="verified" <?php if ($data["Verified"]) {?> checked <?php }?> >
        <br>
        <label for="timeCreated">Account Created: </label>
        <input type="text" name="timeCreated" id="" value="<?=$data["TimeCreated"]?>" disabled>
        <br>
        <label for="lastLogin">Last Login: </label>
        <input type="text" name="lastLogin" id="" value="<?=$data["LastLogin"]?>" disabled>
        <br>
        <label for="tutorialFlag">Tutorial Complete: </label>
        <input type="checkbox" name="tutorialFlag" value="true" id="" <?php if ($data["TutorialFlag"]) {?> checked <?php }?>>
        <input type="submit" value="Save Changes" name="saveUser">
      </form>
      <button id="backBtn">Cancel</button>
      <br>
      <br>
    </div>
    <?php } } ?>

    <!-- create run menu -->
    <?php if ($method == "GET"&& isset($_GET["createRun"])) {?>
    <div id="runDataGenerator">
      <form action="" method="post">
        <label for="runID">RunID number</label>
        <input type="text" name="runID" id="" value="123" disabled>
        <br>
        <label for="userID">UserID number</label>
        <input type="text" name="userID" id="" value="004">
        <br>
        <label for="score">Score</label>
        <input type="text" name="score" id="scoreTxt" value="0">
        <br>
        <label for="levelReached">Level Reached</label>
        <input type="text" name="levelReached" id="" value="0">
        <br>
        <label for="seed">Seed</label>
        <input type="text" name="seed" value="0">
        <br>
        <label for="mode">Mode</label>
        <select name="mode" id="modeSelect"><?php 
          $modeList = getModes($pdo);
          while ($mode = $modeList->fetch()) {
            ?>
            <option value="<?= $mode['ModeID'] ?>"><?= $mode["Mode"] ?>(<?= $mode['ModeID'] ?>)</option><?php
          }
          ?></select><br>
        <input type="submit" value="Save" name="saveRun">
      </form>
      <button id="backBtn">Cancel</button>
      <br>
      <br>
    </div>
    <?php } ?>

    <!--create mode menu -->
    <?php if ($method == "GET"&& isset($_GET["createMode"])) {?>
      <div id="modeEditor">
      <form action="" method="post">
        <label for="modeName">Mode</label>
        <input type="text" name="modeName" id="" value="classic">
        <input type="submit" value="Save" name="saveMode">
      </form>
      <button id="backBtn">Cancel</button>
      <br>
      <br>
    </div>
    <?php } ?> 
  </div>
  <?php } ?> 

  <?php if ($method == "POST" && isset($_POST["saveUser"])) {
    $userID = trim(sanitizeString(INPUT_POST,"userID"));
    $username = trim(sanitizeString(INPUT_POST,"username"));
    $email = trim(sanitizeString(INPUT_POST,"email"));
    $verified = isset($_POST["verified"]);
    $tutorialFlag = isset($_POST["tutorialFlag"]);

    try {
      updateUser($userID, $username, $email, $verified, $tutorialFlag, $pdo);
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit;
    }

    header("Location: maintenanceMenu.php");
    exit;
  } 
  elseif ($method == "POST" && isset($_POST["saveRun"])) {
    $userID = trim(sanitizeInt(INPUT_POST,"userID"));
    $score = trim(sanitizeInt(INPUT_POST,"score"));
    $levelReached = trim(sanitizeInt(INPUT_POST,"levelReached"));
    $seed = trim(sanitizeInt(INPUT_POST,"seed"));
    $mode = trim(sanitizeInt(INPUT_POST,"mode"));

    try {
      createRun($userID, $score, $levelReached, $seed, $mode, $pdo);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }

    header("Location: maintenanceMenu.php");
    exit;
  }
  elseif ($method == "POST" && isset($_POST["saveMode"])) {
    $modeName = trim(sanitizeString(INPUT_POST,"modeName"));

    try {
      createMode($modeName, $pdo);
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit;
    }
    header("Location: maintenanceMenu.php");
    exit;
  }?> 

  <script src="../js/maintenanceMenu.js"></script>

</body>
</html>