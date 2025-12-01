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
  $seed = $_SESSION['seed'];
  $mode = $_SESSION['mode'];

  //TODO: get seed and mode data instead of placeholders
  try {
    createRun($userID, $score, $levelReached, $seed, $mode, $pdo);
  } catch (PDOException $e) {
    throw $e;
  }
