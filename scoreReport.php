<?php 
  require 'dbConnect.php';
  $score = $_POST["score"];
  $levelReached = $_POST["round"];
  $userID = 1;
  $seed = 0;
  $mode = 1;

  //TODO: get userID and mode data instead of placeholders
  try {
    createRun($userID, $score, $levelReached, $seed, $mode, $pdo);
  } catch (PDOException $e) {
    echo $e->getMessage();
    throw $e;
  }
