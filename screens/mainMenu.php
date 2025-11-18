<?php if (!isset($_SESSION["loggedIn"])) {
  $_SESSION["loggedIn"] = "false";
}
?> 
    <div class="buttons mainMenu">
      <h3>Logged in?</h3>
      <button id="startBtn" class="mainMenuBtn">Start Run</button> 
      <?php 
        if (!$_SESSION["loggedIn"]) {
      ?> 
      <h3>Need to make an account?</h3>
      <button id="loginBtn" class="mainMenuBtn">Login</button>  
      <?php } ?> 
      <h3>Change Settings?</h3>
      <button id="configBtn" class="mainMenuBtn">Config</button>
      <h3>Dev tools:</h3>
      <button id="maintBtn" class="mainMenuBtn">Maintenance</button>
    </div>
    <?php
    include "configMenu.php";
    ?>
    <div class="buttons">
      <button id="backBtn" hidden=true>Back</button>
    </div>