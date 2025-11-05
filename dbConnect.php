<?php 
  try {
    //TODO: Change this to remote SQL server when we have one
    //is currently set to my information
    //also requires the database to already exist
    $pdo = new PDO('mysql:host=localhost:3306;dbname=puzzlemaster', 'root', 'mysql');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  } catch (PDOException $e){
    echo "<h1>Unable to connect to database</h1>";
  }


  function addNewUser($username, $email, $password, $pdo) {
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
?> 