<?php 
  if (!session_id()) {
    session_start();
  }
  if (!isset($_SESSION["inUse"]) || !isset($_SESSION["emailWrong"])) {
    $_SESSION["inUse"] = "";
    $_SESSION["emailWrong"] = "";
  }

  require 'sanitize.php';
  require 'dbConnect.php';

  $method = $_SERVER['REQUEST_METHOD'];
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Puzzlemaster: Register</title>
</head>
    <body>
      <div class="wrapper">
        <?php 
          if ($method == 'GET') {
        ?> 
        <form action="" method="post">
          <?php if ($_SESSION['inUse']) {
            echo "<p style=color:red;>That username or email is already in use</p>";
            $_SESSION['inUse'] = false;
          }

          if ($_SESSION['emailWrong']) {
            echo "<p style=color:red;>Please enter a vaild email address</p>";
            $_SESSION["emailWrong"] = false;
          }
          ?> 
          <label for="email">Email</label>
          <input type="text" placeholder="you@email.com" name="email" required maxlength="50">
          <br>
          <label for="username">Username</label>
          <input type="text" placeholder="username" name="username" required maxlength="24"> 
          <br>
          <label for="password">Password</label>
          <input type="password" name="password" required maxlength="60" minlength="6">
          <br><br>
          <input type="submit" value="Register">
        </form>
        <?php } else if ($method == 'POST') { 
          $username = trim(sanitizeString(INPUT_POST, 'username'));
          $email = trim(sanitizeString(INPUT_POST,'email'));
          $password = trim(sanitizeString(INPUT_POST, 'password'));

          

          //check if any of the fields are empty, if so, redirect back to the form page
          if (!$username || !$email || !$password) {
            header('Location: register.php');
            exit;
          }

          $password = password_hash($password, PASSWORD_DEFAULT);

          //check if email is a proper email
          $emailValid = filter_var($email, FILTER_VALIDATE_EMAIL);
          if ($emailValid == false) {
            $_SESSION['emailWrong'] = true;
            header('Location: register.php');
            exit;
          }

          //checks, via the function in dbConnect.php, if the username or email is 
          //already used
          $available = checkAvailability($username, $email, $pdo);
          if (!$available) {
            $_SESSION['inUse'] = true;
            header('Location: register.php');
            exit;
          }
          
          //adds user to the database
          addNewuser($username, $email, $password, $pdo);
          //redirect to the main page here

        }
        ?> 
      </div>
    </body>
</html>