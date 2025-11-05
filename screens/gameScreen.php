<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gameScreen</title>
</head>
<body>
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
                <li><img src="assets/gamepieces/gamepiece01" 
                alt="triangle gem"></li>
                <li><img src="assets/gamepieces/gamepiece02" 
                alt="square gem"></li>
                <li><img src="assets/gamepieces/gamepiece03"
                alt="daimond gem"></li>
                <li><img src="assets/gamepieces/gamepiece04" 
                alt="hexagon gem"></li>
                <li><img src="assets/gamepieces/gamepiece05" 
                alt="octagon gem"></li>
            </ul>
        </aside>
        
        <!-- defining the game area -->
        <table class="gameArea">
            <thead>
                <tr>
                    <th scope="col" name="col1"></th>
                    <th scope="col" name="col2"></th>
                    <th scope="col" name="col3"></th>
                    <th scope="col" name="col4"></th>
                    <th scope="col" name="col5"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row" name="row1">
                        <td>X</td><td>X</td><td>X</td><td>X</td><td>X</td>
                    </th>
                </tr>
                <tr>
                    <th scope="row" name="row2">
                        <td>X</td><td>X</td><td>X</td><td>X</td><td>X</td>
                    </th>
                </tr>
                <tr>
                    <th scope="row" name="row3">
                        <td>X</td><td>X</td><td>X</td><td>X</td><td>X</td>
                    </th>
                </tr>
                <tr>
                    <th scope="row" name="row4">
                        <td>X</td><td>X</td><td>X</td><td>X</td><td>X</td>
                    </th>
                </tr>
                <tr>
                    <th scope="row" name="row5">
                        <td>X</td><td>X</td><td>X</td><td>X</td><td>X</td>
                    </th>
                </tr>
            </tbody>
        </table>
    </main>
    
</body>
</html>