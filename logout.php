<?php 
  session_start();
  if (!isset($_SESSION["loggedIn"])) {
    $_SESSION["loggedIn"] = "";
  }

  $_SESSION["loggedIn"] = false;
  $_SESSION["admin"] = false;
  $_SESSION["userID"] = "";


  header("Location: index.php");
  exit;
