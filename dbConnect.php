<?php 
  try {
    //TODO: Change this to remote SQL server when we have one
    //is currently set to my information
    //also requires the database to already exist
    $pdo = new PDO('mysql:host=localhost:3306;dbname=puzzlemaster', 'root', 'mysql');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  } catch (PDOException $e){
    echo $e->getMessage();
  }

  function addNewUser($username, $email, $password, $pdo) {
    date_default_timezone_set("America/Chicago");
    $date = date("Y-m-d H:i:s");
    try {
      $pdo->beginTransaction();
      $query = "INSERT INTO `user` (username, email, `password`, verified, TimeCreated, LastLogin, TutorialFlag)
      VALUES (?,?,?,?,?,?,?);";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$username, $email, $password, 0, $date, $date, 1]);
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollBack();
      echo $e->getMessage();
    }
  }

  function checkAvailability($username, $email, $pdo) {
    $query = "SELECT username, email FROM `user`
    WHERE username = '$username' OR email='$email'";
    
    $result = $pdo->query($query);
    if ($result->rowCount() == 0) {
      return true;
    } else {
      return false;
    }
  }

  function checkUser($username, $password, $pdo) {
    $query = "SELECT `password` FROM `user`
    WHERE username = '$username'";

    $result = $pdo->query($query);

    if ($result->rowCount() == 0) {
      return 'username';
    }

    $databasePassword = $result->fetchColumn();
    if (password_verify($password, $databasePassword)) {
      return 'accepted';
    } else {
      return 'password';
    }
  }

  function updateLogin($username, $pdo) {
    date_default_timezone_set("America/Chicago");
    $date = date("Y-m-d H:i:s");
    $query = "UPDATE `user` SET LastLogin = '$date'
    WHERE UserID = (SELECT UserID FROM `user` WHERE Username=`$username`)";
    echo $query;
    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare($query);
      $stmt->execute();
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollBack();
      echo $e->getMessage();
    }
  }

  function getUserHighScores($username, $pdo) {
    $query = "SELECT score FROM runhistory
    WHERE UserID = (
      SELECT UserID FROM `user`
      WHERE username = `$username`
      )
    ORDER BY score DESC";

    $result = $pdo->query($query);
  }

  function getHighScores($pdo) {
    $query = "SELECT score, username 
    FROM runhistory INNER JOIN `user` USING(UserID)
    ORDER BY score DESC";

    $result = $pdo->query($query);
  }
  
  // Returns the full contents of the runHistory table along with the Username corresponding to each UserID.
  function getFullLeaderboard($pdo) {
    $query = "SELECT RunID, UserID, Score, LevelReached, TimeOf, Seed, ModeID, user.Username 
      FROM puzzlemaster.runhistory
      LEFT JOIN user USING (UserID)
      ORDER BY RunID ASC";

      return $pdo->query($query);
  }

  // Returns the predicted value of the RunID. Used for displaying the predicted runID when creating entires in runHistory.
  // Might remove as this as we now auto-increment RunID so it doesn't need to be queried.
  function getNextRunID($pdo) {
    $query = "SELECT RunID
      FROM runhistory
      ORDER BY RunID DESC
      LIMIT 1";

      $result = $pdo->query($query)->fetch();
      if ($result >= 0) {
        return $result;
      } else {
        return 0;
      }
  }

  // Returns a fetched run based off the input RunID.
  function getRunByID($runID, $pdo) {
    $query = "SELECT RunID, UserID, Score, LevelReached, TimeOf, Seed, ModeID 
    FROM puzzlemaster.runhistory
    WHERE RunID = ?
    LIMIT 1";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$runID]);

    return $stmt->fetch();
  }

 function getModes($pdo) {
    $query = "SELECT ModeID, Mode FROM gamemode";
    return $pdo->query($query);
  }
  
  function getUserDataByUsername($username, $pdo) {
    $query = "SELECT * FROM `user` 
    WHERE username=?";
    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare($query);
      $stmt->execute([$username]);
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollBack();
      throw $e;
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  function updateUser($userID, $username, $email, $verified, $tutorialFlag, $pdo) {
    if ($tutorialFlag) {
      $tutorialFlag = 1;
    } else {
      $tutorialFlag = 0;
    }

    if ($verified) {
      $verified = 1;
    } else {
      $verified = 0;
    }

    $query = "UPDATE `user`
    SET username = ?, email = ?, verified = ?, TutorialFlag = ?
    WHERE UserID = $userID";
    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare($query);
      $stmt->execute([$username, $email, $verified, $tutorialFlag]);
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  // Creates a runHistory entry. RunID and TimeOf are computed.
  function createRun($userID, $score, $levelReached, $seed, $mode, $pdo) {
    $query = "INSERT INTO runhistory (UserID, score, levelreached, timeof, seed, modeID)
    VALUES (?, ?, ?, ?, ?, ?);";
    date_default_timezone_set("America/Chicago");
    $timeof = date("Y-m-d H:i:s");

    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare($query);
      $stmt->execute([$userID, $score, $levelReached, $timeof, $seed, $mode]);
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  function createMode($modeName, $pdo) {
    $query = "INSERT INTO gamemode (Mode) 
    VALUES (?);";

    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare($query);
      $stmt->execute([$modeName]);
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  // Updates the contents of one row in runHistory with the exception of RunID and UserID.
  function editRun($runID, $score, $levelReached, $timeOf, $seed, $modeID, $pdo) {
    $query = "UPDATE runHistory
    SET Score = ?, LevelReached = ?, TimeOf = ?, Seed = ?, ModeID = ?
    WHERE RunID = $runID";
    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare($query);
      $stmt->execute([$score, $levelReached, $timeOf, $seed, $modeID]);
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollBack();
      throw $e;
    }
  }
?> 