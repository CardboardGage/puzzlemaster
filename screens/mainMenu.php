
    <div class="buttons startMenu">
      <button class="backBtn">Back</button>
      <form action="../screens/gameScreen.php" method="post">
        <input id="startGameBtn" type="submit" name="startGameBtn" value="start">
      </form>
    </div>
    <div class="buttons mainMenu">
      <h3>Logged in?</h3>
      <button id="startBtn" class="mainMenuBtn">Start Run</button> 
      <h3>Need to make an account?</h3>
      <button id="loginBtn" class="mainMenuBtn">Login</button>  
      <h3>Change Settings?</h3>
      <button id="configBtn" class="mainMenuBtn">Config</button>
      <h3>Dev tools:</h3>
      <button id="maintBtn" class="mainMenuBtn">Maintenance</button>
    </div>
      <?php
      include "loginMenu.php";
      include "configMenu.php";
      ?>