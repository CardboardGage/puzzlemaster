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

  function getFullLeaderboard($pdo) {
    $query = "SELECT RunID, UserID, Score, LevelReached, TimeOf, Seed, ModeID, user.Username 
      FROM puzzlemaster.runhistory
      LEFT JOIN user USING (UserID)
      ORDER BY RunID ASC";

      return $pdo->query($query);
  }
?> 