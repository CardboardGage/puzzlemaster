<?php session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PuzzleMaster</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="css/mainMenu.css">
  <?php 
  $_SESSION["state"] = 0;
  if (!isset($_SESSION["loggedIn"])) {
    $_SESSION["loggedIn"] = false;
  }

  if (!isset($_SESSION["admin"])) {
    $_SESSION["admin"] = false;
  }

  require "dbConnect.php";
  usersEmpty($pdo);
?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    
  <div class="wrapper">
    <div class="buttons mainMenu">
      <h3>Logged in?</h3>
      <button id="startBtn" class="mainMenuBtn">Start Run</button> 
      <?php 
        if (!$_SESSION["loggedIn"] && isset($pdo)) {
      ?> 
      <h3>Need to make an account?</h3>
      <button id="loginBtn" class="mainMenuBtn">Login</button>  
      <?php } else {?>
      <button id="logoutBtn" class="mainMenuBtn">Log Out</button>
      <?php } ?> 
      <h3>Change Settings?</h3>
      <button id="configBtn" class="mainMenuBtn">Config</button><?php
        if ($_SESSION["loggedIn"] && isset($pdo) && $_SESSION["admin"]) {
      ?>
      <h3>Dev tools:</h3>
      <button id="maintBtn" class="mainMenuBtn">Maintenance</button>
      <?php } ?>
    </div>
    <?php
    include "screens/configMenu.php";
    ?>
    <div class="buttons">
      <button id="backBtn" hidden=true>Back</button>
    </div>
    <?php
      include "screens/leaderboard.php";
      ?>
  </div>
  <script src="js/menu.js"></script>
</body>
</html>
