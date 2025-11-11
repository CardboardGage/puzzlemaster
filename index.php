<?php session_start(); ?>

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
  
?>

</head>

<body>
    
  <div class="wrapper">
      <?php
      include "screens/mainMenu.php";
      ?>
  </div>

  <script src="js/menu.js"></script>
</body>
</html>
