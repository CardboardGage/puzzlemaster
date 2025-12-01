    <?php 
      $modes = getModes($pdo);
      $method = $_SERVER['REQUEST_METHOD'];
      if ($method == 'GET') {
    ?> 
    <div class="buttons configMenu">
      <div class="option">
        <form action="" method="post">
          <label for="seed">Run Seed:</label>
          <input type="text" name="seed" maxlength="16">
          <input type="submit" value="Save">
        </form>
      </div>
    </div>

    <?php } else if ($method == "POST") {
      if (!isset($_SESSION['seed'])) {
        $_SESSION['seed'] = "";
      }
      $_SESSION["seed"] = $_POST["seed"];
      if ($_POST['seed']) {
        $_SESSION['mode'] = 2;
      } else {
        $_SESSION['mode'] = 1;
      }

      header('Location: index.php');
      exit;
    }
