<?php 
  if (!session_id()) {
    session_start();
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
          <label for="email">Email</label>
          <input type="text" placeholder="you@email.com" name="email">
          <br>
          <label for="username">Username</label>
          <input type="text" placeholder="username" name="username"> 
          <br>
          <label for="password">Password</label>
          <input type="text" name="password">
          <br><br>
          <input type="submit" value="Register">
        </form>
        <?php } else if ($method == 'POST') { 
          $username = sanitizeString(INPUT_POST, 'username');
          $email = sanitizeString(INPUT_POST,'email');
          $password = sanitizeString(INPUT_POST, 'password');
          
          addNewuser($username, $email, $password, $pdo);
          

        }
        ?> 
      </div>
    </body>
</html>