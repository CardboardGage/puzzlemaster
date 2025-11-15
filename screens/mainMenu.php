<?php if (!isset($_SESSION["loggedIn"])) {
  $_SESSION["loggedIn"] = "false";
}
?> 
    <div class="buttons">
        <h3>Logged in?</h3>
        <button id="startBtn" class="mainMenu">Start Run</button> 
        <?php 
          if (!$_SESSION["loggedIn"]) {
        ?> 
        <h3>Need to make an account?</h3>
        <button id="loginBtn" class="mainMenu">Login</button>  
        <?php } ?> 
        <h3>Change Settings?</h3>
        <button id="configBtn" class="mainMenu">Config</button>
        <h3>Dev tools:</h3>
        <button id="mainBtn" class="mainMenu">Maintenance</button>
        <button id="backBtn" hidden=true>Back</button>
    </div>
    
    <script src="../js/menu.js"></script>
