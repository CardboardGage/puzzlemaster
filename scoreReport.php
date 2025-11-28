<?php 
  require 'dbConnect.php';
  session_start();
  $score = $_POST["score"];
  $levelReached = $_POST["round"];

  if (!isset($_SESSION["userID"])) {
    $userID = 0;
  } else {
    $userID = $_SESSION["userID"];
  }
  $seed = 0;
  $mode = 1;

  //TODO: get userID and mode data instead of placeholders
  try {
    createRun($userID, $score, $levelReached, $seed, $mode, $pdo);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
