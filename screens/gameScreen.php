<php> </php>
<?php
session_start();
if (!isset($_SESSION["seed"])) {
    $_SESSION['seed'] = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gameScreen</title>
    <link rel="stylesheet" href="../css/gameScreen.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <!-- menu button to pull up options/settings
            menu -->
            <input type="button" value="<- Menu" id="menuButton">
            <!-- just using placeholders right now 
            for whatever we hook these outputs up to -->
            <div class="level">
                <p>Level</p>
                <output name="Level: " for="level">01</output>
            </div>
            <div class="moves">
                <p>Moves Left</p>
                <output name="Moves: " for="moves">00</output>
            </div>
            <div class="total">
                <p>Total Points</p>
                <output name="Total: " for="total">0000</output>
            </div>
            <div class="score">
                <p>Round Points</p>
                <output name="Score: " for="score">000</output>
            </div>
            <div class="target">
                <p>Target</p>
                <output name="Target: " for="target">00</output>
            </div>
        </header>
        <main>
            <aside>
                <!-- //// How pieces work ///// -->
                <ul>
                    <li><img src="../assets/gamepieces/gamepiece01.png"
                            alt="triangle gem"></li>
                    <li>can move horizontally, veritcally, and diagnoally</li>
                    <li><img src="../assets/gamepieces/goldPowerup.png"
                            alt="gold powerup"></li>
                    <li>can collect gold for bonus points for this round and the next</li>
                    <li><img src="../assets/gamepieces/pickaxePowerup.png"
                            alt="pickaxe powerup"></li>
                    <li>can break all gems in a horizontal row</li>
                    <li><img src="../assets/gamepieces/tntPowerUp.png"
                            alt="tnt powerup"></li>
                    <li>can break all gems in a 4x4 area</li>
                </ul>
            </aside>

            <!-- defining the game area -->
            <div class="gameArea">
                <div id="phaser-container"></div>

                <script src="../js/phaser.js"></script>
                <script src="../js/matchLogic.js"></script>
                <script src="../js/phaserTest.js"></script>
            </div>
        </main>
    </div>
    <aside hidden id="seed"><?= $_SESSION['seed'] ?></aside>
</body>
</html>
