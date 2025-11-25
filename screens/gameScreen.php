<php> </php>

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
            <input type="button" value="menu">
            <!-- just using placeholders right now 
            for whatever we hook these outputs up to -->
            <output name="Score: " for="score">00000</output>
            <output name="Lvl: " for="level">01</output>
        
        </header>
        <main>
            <aside>
                <!-- //// How pieces work ///// -->
                <ul>
                    <li><img src="../assets/gamepieces/gamepiece01.jpg" 
                    alt="triangle gem"></li>
                    <li><img src="../assets/gamepieces/gamepiece02.jpg" 
                    alt="square gem"></li>
                    <li><img src="../assets/gamepieces/gamepiece03.jpg"
                    alt="daimond gem"></li>
                    <li><img src="../assets/gamepieces/gamepiece04.jpg" 
                    alt="hexagon gem"></li>
                    <li><img src="../assets/gamepieces/gamepiece05.jpg" 
                    alt="octagon gem"></li>
                </ul>
            </aside>
            
            <!-- defining the game area -->
            <div class="gameArea">
                <script src="../js/phaser.js"></script>
                <script src="../js/matchLogic.js"></script>
                <script src="../js/phaserTest.js"></script>
            </div>
        </main>
    </div>
</body>
</html>
