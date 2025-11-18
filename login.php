<?php 
  if (!session_id()) {
    session_start();
  }

  require "dbConnect.php";
  require "sanitize.php";

  $method = $_SERVER['REQUEST_METHOD'];
  if (!isset($_SESSION["username"]) || !isset($_SESSION["password"])) {
    $_SESSION["username"] = "";
    $_SESSION["password"] = "";
    $_SESSION["loggedIn"] = false;
  }

  if ($_SESSION["loggedIn"]) {
    header("Location: index.php");
    exit();
  }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Puzzlemaster - Login</title>
  <link rel="stylesheet" href="css/mainMenu.css">
</head>

<?php 
  if ($method == 'GET') {
?> 
<body>
  <div class="wrapper">
    <?php 
      if ($_SESSION['username']) {
    ?> 
    <p style="color:red">Incorrect Username</p>
    <?php 
      $_SESSION['username'] = '';
    }
    ?>

    <?php 
      if ($_SESSION['password']) {
    ?> 
    <p style="color:red">Incorrect Password</p>
    <?php 
      $_SESSION['password'] = '';
    }
    ?>     

    <form action="" method="post">
      <label for="username">Username:</label>
      <br>
      <input type="text" name="username" required maxlength="24">
      <br>
      <label for="password">Password:</label>
      <br>
      <input type="password" name="password" required maxlength="60" minlength="6">
      <br><br>
      <input type="submit" value="Log In" id="logIn">
    </form>

    <form action="register.php" method="get">
      <label for="submit">Don't have an account?</label>
      <input type="submit" value="Register" name="submit">
    </form>
  </div>
</body>
<?php 
  } else if ($method == 'POST') {
    $username = trim(sanitizeString(INPUT_POST, 'username'));
    $password = trim(sanitizeString(INPUT_POST,'password'));

    if ($username == ''|| $password == '') {
      header('Location: login.php');
      exit;
    }

    $result = checkUser($username, $password, $pdo);
    if ($result == 'username') {
      $_SESSION['username'] = true;
      header('Location: login.php');
      exit;
    } else if ($result == 'password') {
      $_SESSION['password'] = true;
      header('Location: login.php');
      exit;
    } else if ($result == 'accepted') {
      $_SESSION["loggedIn"] = true;
      //TODO: redirect to destination page here
      header("Location: index.php");
      exit;
    }
  }
?> 
</html>
