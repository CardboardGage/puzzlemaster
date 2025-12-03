const ROWS = 8;
const COLS = 8;
const TILE_SIZE = 64; // adjust to fit your background

// Round / Score settings
const MOVES_PER_ROUND = 10; // moves allowed per round
const BASE_TARGET_SCORE = 500; // target score for round 1
const TARGET_INCREMENT = 250; // how much the target increases each round

const POWERUP_CHANCE_PERCENT = 5; // % chance any new gem is a power-up (tweakable)

const POWERUP_TYPES = ["gold", "pickaxe", "tnt"];

// Gem keys used in the sprites
const GEM_KEYS = [
  "triangleGem",
  "squareGem",
  "diamondGem",
  "hexagonGem",
  "octogonGem",
];

// PHASER LIFECYCLE
//  PHASER LIFECYCLE
function preload() {
  // Background board image
  this.load.image("gamegrid", "../assets/gamepieces/gamegrid.jpg");

  this.load.image("caveWall", "../assets/background/caveWall.jpg");

  // Gem images
  // Gem images (corrected to .png)
  this.load.image("triangleGem", "../assets/gamepieces/gamepiece01.png");
  this.load.image("squareGem", "../assets/gamepieces/gamepiece02.png");
  this.load.image("diamondGem", "../assets/gamepieces/gamepiece03.png");
  this.load.image("hexagonGem", "../assets/gamepieces/gamepiece04.png");
  this.load.image("octogonGem", "../assets/gamepieces/gamepiece05.png");

  this.load.image("goldPowerup", "../assets/gamepieces/goldPowerup.png");
  this.load.image("pickaxePowerup", "../assets/gamepieces/pickaxePowerup.png");
  this.load.image("tntPowerUp", "../assets/gamepieces/tntPowerUp.png");
}

function create() {
  let seedTag = document.querySelector("#seed");
  let seed = seedTag.innerHTML;
  // Draw background and size it to match the grid area
  // Full-screen cave wall background
  this.add
    .image(0, 0, "caveWall")
    .setOrigin(0, 0)
    .setDisplaySize(this.scale.width, this.scale.height);

  if (seed) {
    this.rng = new Phaser.Math.RandomDataGenerator([seed]);
  } else {
    this.rng = new Phaser.Math.RandomDataGenerator();
  }

  this.domLevelOutput = document.querySelector(".level output");
  this.domMovesOutput = document.querySelector(".moves output");
  this.domTotalOutput = document.querySelector(".total output");
  this.domTargetOutput = document.querySelector(".target output");
  this.domScoreOutput = document.querySelector(".score output");

  // Game state
  this.round = 1;
  this.score = 0; // round score (resets each round)
  this.totalScore = 0; // cumulative score across rounds
  this.highScore = 0; // best totalScore (for DB later)
  this.movesLeft = MOVES_PER_ROUND;
  this.targetScore = BASE_TARGET_SCORE;
  this.isProcessingMove = false;
  this.isGameOver = false;

  // 🔹 power-up / score buff state
  this.doubleScoreActive = false;
  this.doubleScoreRoundsLeft = 0; // how many FUTURE rounds the buff lasts for

  // Board and selection
  this.board = [];
  this.selectedGem = null;

  // UI Text (below the grid)
  this.scoreText = this.add.text(20, ROWS * TILE_SIZE + 10, "Score: 0", {
    fontSize: "20px",
    fill: "#ffffff",
  });

  this.roundText = this.add.text(
    20,
    ROWS * TILE_SIZE + 35,
    "Round: " + this.round,
    { fontSize: "20px", fill: "#ffffff" }
  );

  this.targetText = this.add.text(
    220,
    ROWS * TILE_SIZE + 10,
    "Target: " + this.targetScore,
    { fontSize: "20px", fill: "#ffffff" }
  );

  this.movesText = this.add.text(
    220,
    ROWS * TILE_SIZE + 35,
    "Moves: " + this.movesLeft,
    { fontSize: "20px", fill: "#ffffff" }
  );

  this.totalScoreText = this.add.text(420, ROWS * TILE_SIZE + 10, "Total: 0", {
    fontSize: "20px",
    fill: "#ffffff",
  });

  this.highScoreText = this.add.text(420, ROWS * TILE_SIZE + 35, "High: 0", {
    fontSize: "20px",
    fill: "#ffffff",
  });

  // Build the initial board
  startNewRound(this);

  // Input
  this.input.on("gameobjectdown", handleGemDown, this);
}

function update() {
  // Nothing needed per frame right now
}

function updateDomHeader(scene) {
  if (scene.domLevelOutput) {
    scene.domLevelOutput.value = scene.round.toString().padStart(2, "0");
  }
  if (scene.domMovesOutput) {
    scene.domMovesOutput.value = scene.movesLeft.toString().padStart(2, "0");
  }
  if (scene.domTargetOutput) {
    scene.domTargetOutput.value = scene.targetScore.toString();
  }
  if (scene.domScoreOutput) {
    scene.domScoreOutput.value = scene.score.toString().padStart(4, "0");
  }
  if (scene.domTotalOutput) {
    scene.domTotalOutput.value = scene.totalScore.toString().padStart(4, "0");
  }
}

// Create a gem at [row,col] that does NOT immediately make a 3-in-a-row
function createNonMatchingGem(scene, row, col) {
  let gem = null;
  let attempts = 0;
  const board = scene.board;

  while (true) {
    // Destroy previous candidate if any
    if (gem) {
      gem.destroy();
    }

    // Use your existing random gem creator
    gem = createRandomGem(scene, row, col);
    const key = gem.getData("key");

    const left1 = col >= 1 ? board[row][col - 1] : null;
    const left2 = col >= 2 ? board[row][col - 2] : null;
    const up1 = row >= 1 ? board[row - 1][col] : null;
    const up2 = row >= 2 ? board[row - 2][col] : null;

    const formsHorizontalMatch =
      left1 &&
      left2 &&
      left1.getData("key") === key &&
      left2.getData("key") === key;

    const formsVerticalMatch =
      up1 && up2 && up1.getData("key") === key && up2.getData("key") === key;

    if (!formsHorizontalMatch && !formsVerticalMatch) {
      // Safe: placing this gem does not create a 3+ run
      break;
    }

    attempts++;
    if (attempts > 20) {
      // Just in case (very unlikely with 5 gem types)
      break;
    }
  }

  return gem;
}

function startNewRound(scene) {
  // Destroy any existing gems (in case of new round)
  if (scene.board && scene.board.length) {
    for (let row = 0; row < ROWS; row++) {
      for (let col = 0; col < COLS; col++) {
        const gem = scene.board[row] && scene.board[row][col];
        if (gem) {
          gem.destroy();
        }
      }
    }
  }

  // Reset the board array
  scene.board = [];
  for (let row = 0; row < ROWS; row++) {
    scene.board[row] = [];
    for (let col = 0; col < COLS; col++) {
      // 🔹 Use the no-match creator instead of plain createRandomGem
      const gem = createNonMatchingGem(scene, row, col);
      scene.board[row][col] = gem;
    }
  }

  // Reset round-based values
  scene.score = 0;
  scene.movesLeft = MOVES_PER_ROUND;
  scene.targetScore = BASE_TARGET_SCORE + (scene.round - 1) * TARGET_INCREMENT;
  scene.selectedGem = null;
  scene.isProcessingMove = false;
  scene.isGameOver = false; // if you added game over

  // Update text UI
  scene.scoreText.setText("Score: " + scene.score);
  scene.roundText.setText("Round: " + scene.round);
  scene.targetText.setText("Target: " + scene.targetScore);
  scene.movesText.setText("Moves: " + scene.movesLeft);

  // 🔹 Sync DOM header with current values
  updateDomHeader(scene);

  // 🔹 handle double-score buff duration
  if (scene.doubleScoreActive) {
    scene.doubleScoreRoundsLeft--;
    if (scene.doubleScoreRoundsLeft <= 0) {
      scene.doubleScoreActive = false;
    }
  }

  // If you have these:
  if (scene.totalScoreText) {
    scene.totalScoreText.setText("Total: " + scene.totalScore);
  }
  if (scene.highScoreText) {
    scene.highScoreText.setText("High: " + scene.highScore);
  }
}

function createRandomGem(scene, row, col) {
  let key;
  let type = "gem";
  let powerupType = null;

  // 🔹 Decide if this spawn is a power-up
  const roll = scene.rng.between(1, 100);
  if (roll <= POWERUP_CHANCE_PERCENT) {
    // choose one of the 3 power-up types
    const choice = scene.rng.pick(POWERUP_TYPES);
    if (choice === "gold") {
      key = "goldPowerup";
      type = "powerup";
      powerupType = "gold";
    } else if (choice === "pickaxe") {
      key = "pickaxePowerup";
      type = "powerup";
      powerupType = "pickaxe";
    } else if (choice === "tnt") {
      key = "tntPowerUp";
      type = "powerup";
      powerupType = "tnt";
    }
  }

  // If not chosen as power-up, or something went wrong, use a normal gem
  if (!key) {
    key = scene.rng.pick(GEM_KEYS);
    type = "gem";
    powerupType = null;
  }

  const x = col * TILE_SIZE + TILE_SIZE / 2;
  const y = row * TILE_SIZE + TILE_SIZE / 2;

  const gem = scene.add.image(x, y, key);
  gem.setDisplaySize(TILE_SIZE, TILE_SIZE);

  gem.setData("row", row);
  gem.setData("col", col);
  gem.setData("key", key);
  gem.setData("type", type); // 'gem' or 'powerup'
  gem.setData("powerupType", powerupType); // 'gold' | 'pickaxe' | 'tnt' | null
  gem.setInteractive();

  return gem;
}

function handleGemDown(pointer, gem) {
  const scene = this;

  if (scene.isGameOver) return;
  // Don't let user click during animations / resolving
  if (scene.isProcessingMove) return;

  // No moves left -> wait until round resets
  if (scene.movesLeft <= 0) return;

  // 🔹 If this is a power-up, use it instead of selecting/swapping
  if (gem.getData("type") === "powerup") {
    usePowerup(scene, gem);
    return;
  }

  // No gem selected yet: select this one
  if (!scene.selectedGem) {
    scene.selectedGem = gem;
    gem.setTint(0xffff00); // highlight

    // 🔹 small pulse animation
    const baseScaleX = gem.scaleX;
    const baseScaleY = gem.scaleY;

    scene.tweens.add({
      targets: gem,
      scaleX: { from: baseScaleX, to: baseScaleX * 1.15 },
      scaleY: { from: baseScaleY, to: baseScaleY * 1.15 },
      duration: 120,
      yoyo: true,
      repeat: 0,
    });

    return;
  }

  // Clicked the same gem again: deselect it
  if (scene.selectedGem === gem) {
    gem.clearTint();
    scene.selectedGem = null;
    return;
  }

  const g1 = scene.selectedGem;
  const g2 = gem;

  // Clear highlight
  g1.clearTint();
  scene.selectedGem = null;

  const r1 = g1.getData("row");
  const c1 = g1.getData("col");
  const r2 = g2.getData("row");
  const c2 = g2.getData("col");

  const isAdjacent =
    ((Math.abs(c1 - c2) === 1) && (Math.abs(r1 - r2) === 1)) || 
    ((Math.abs(c1 - c2) === 1) && (Math.abs(r1 - r2) === 0)) || 
    ((Math.abs(c1 - c2) === 0) && (Math.abs(r1 - r2) === 1));

  if (!isAdjacent) {
    shakeGem(scene, gem);
    return;
  }

  // Use up one move
  scene.movesLeft--;
  scene.movesText.setText("Moves: " + scene.movesLeft);
  updateDomHeader(scene);

  // Lock input during swap + resolution
  scene.isProcessingMove = true;

  swapGems(scene, g1, g2, false);
}

function usePowerup(scene, gem) {
  if (scene.isProcessingMove || scene.isGameOver) return;

  // Spend a move when using a power-up
  scene.movesLeft--;
  scene.movesText.setText("Moves: " + scene.movesLeft);
  scene.isProcessingMove = true;
  updateDomHeader(scene);

  const pType = gem.getData("powerupType");

  if (pType === "gold") {
    // 🔹 activate double-score for this + next round
    scene.doubleScoreActive = true;
    scene.doubleScoreRoundsLeft = 2;

    // Remove just this gem and let board drop
    destroyGemsAndDrop(scene, [gem]);
  } else if (pType === "pickaxe") {
    // 🔹 break entire row
    const row = gem.getData("row");
    const gemsToDestroy = [];
    for (let c = 0; c < COLS; c++) {
      const g = scene.board[row][c];
      if (g) gemsToDestroy.push(g);
    }
    destroyGemsAndDrop(scene, gemsToDestroy);
  } else if (pType === "tnt") {
    // 🔹 destroy 4x4 area centered on this gem
    const centerRow = gem.getData("row");
    const centerCol = gem.getData("col");
    const gemsToDestroy = [];

    for (let r = centerRow - 1; r <= centerRow + 2; r++) {
      for (let c = centerCol - 1; c <= centerCol + 2; c++) {
        if (r >= 0 && r < ROWS && c >= 0 && c < COLS) {
          const g = scene.board[r][c];
          if (g) gemsToDestroy.push(g);
        }
      }
    }

    destroyGemsAndDrop(scene, gemsToDestroy);
  } else {
    // unknown power-up type: just clear it
    destroyGemsAndDrop(scene, [gem]);
  }
}

function destroyGemsAndDrop(scene, gemList) {
  const board = scene.board;
  const unique = Array.from(new Set(gemList));
  let destroyCount = 0;
  const total = unique.length;

  if (total === 0) {
    scene.isProcessingMove = false;
    checkRoundEnd(scene);
    return;
  }

  // 🔹 50 points per gem destroyed by powerups
  let scoreGained = total * 50;

  // apply double-score buff from gold bar if active
  if (scene.doubleScoreActive) {
    scoreGained *= 2;
  }

  // update round score
  scene.score += scoreGained;
  scene.scoreText.setText("Score: " + scene.score);

  // update cumulative total score
  scene.totalScore += scoreGained;
  scene.totalScoreText.setText("Total: " + scene.totalScore);

  // update high score if needed
  if (scene.totalScore > scene.highScore) {
    scene.highScore = scene.totalScore;
    scene.highScoreText.setText("High: " + scene.highScore);
  }
  updateDomHeader(scene);

  // optional: floating score text at average position of destroyed gems
  let sumX = 0;
  let sumY = 0;
  unique.forEach((gem) => {
    sumX += gem.x;
    sumY += gem.y;
  });
  const cx = sumX / total;
  const cy = sumY / total;

  const floatText = scene.add
    .text(cx, cy, "+" + scoreGained, {
      fontSize: "32px",
      fill: "#ffcc00",
      stroke: "#000000",
      strokeThickness: 4,
    })
    .setOrigin(0.5);

  scene.tweens.add({
    targets: floatText,
    y: cy - 50,
    alpha: 0,
    duration: 700,
    ease: "Cubic.easeOut",
    onComplete: () => floatText.destroy(),
  });

  // now actually destroy the gems and drop the board
  unique.forEach((gem) => {
    const row = gem.getData("row");
    const col = gem.getData("col");

    if (row != null && col != null && board[row][col] === gem) {
      board[row][col] = null;
    }

    // reuse your pop animation
    playPopAnimation(scene, gem, () => {
      destroyCount++;
      if (destroyCount === total) {
        // after all popped, let everything fall and handle cascades
        dropGems(scene);
      }
    });
  });
}

// shake animation for invalid/bad interactions
function shakeGem(scene, gem) {
  scene.tweens.add({
    targets: gem,
    x: gem.x + 5,
    duration: 40,
    yoyo: true,
    repeat: 3,
  });
}

function swapGems(scene, g1, g2, isReversing) {
  let r1 = g1.getData("row");
  let c1 = g1.getData("col");
  let r2 = g2.getData("row");
  let c2 = g2.getData("col");

  // Swap in the logical board array
  scene.board[r1][c1] = g2;
  scene.board[r2][c2] = g1;

  // Swap stored row/col values
  g1.setData("row", r2);
  g1.setData("col", c2);
  g2.setData("row", r1);
  g2.setData("col", c1);

  const g1TargetX = g1.getData("col") * TILE_SIZE + TILE_SIZE / 2;
  const g1TargetY = g1.getData("row") * TILE_SIZE + TILE_SIZE / 2;
  const g2TargetX = g2.getData("col") * TILE_SIZE + TILE_SIZE / 2;
  const g2TargetY = g2.getData("row") * TILE_SIZE + TILE_SIZE / 2;

  // Animate the swap
  scene.tweens.add({
    targets: g1,
    x: g1TargetX,
    y: g1TargetY,
    duration: 150,
  });

  scene.tweens.add({
    targets: g2,
    x: g2TargetX,
    y: g2TargetY,
    duration: 150,
    onComplete: function () {
      const matches = findMatches(scene);
      if (matches.length > 0) {
        // Valid move with matches
        handleMatches(scene, matches);
      } else if (!isReversing) {
        // No match -> reverse the swap (undo the move)
        swapGems(scene, g1, g2, true);
      } else {
        // Finished reversing a no-match swap
        shakeGem(scene, g1);
        shakeGem(scene, g2);
        checkRoundEnd(scene);
      }
    },
  });
}

function findMatches(scene) {
  const board = scene.board;
  const matches = [];

  // Horizontal matches
  for (let row = 0; row < ROWS; row++) {
    let run = [board[row][0]];
    for (let col = 1; col < COLS; col++) {
      const current = board[row][col];
      const previous = board[row][col - 1];

      if (
        current &&
        previous &&
        current.getData("type") === "gem" &&
        previous.getData("type") === "gem" &&
        current.getData("key") === previous.getData("key")
      ) {
        run.push(current);
      } else {
        if (run.length >= 3) {
          matches.push(run.slice());
        }
        run = [current];
      }
    }
    if (run.length >= 3) {
      matches.push(run.slice());
    }
  }

  // Vertical matches
  for (let col = 0; col < COLS; col++) {
    let run = [board[0][col]];
    for (let row = 1; row < ROWS; row++) {
      const current = board[row][col];
      const previous = board[row - 1][col];

      if (
        current &&
        previous &&
        current.getData("type") === "gem" &&
        previous.getData("type") === "gem" &&
        current.getData("key") === previous.getData("key")
      ) {
        run.push(current);
      } else {
        if (run.length >= 3) {
          matches.push(run.slice());
        }
        run = [current];
      }
    }
    if (run.length >= 3) {
      matches.push(run.slice());
    }
  }

// Diagonal Matches 
    for(let col = 0, row = 0; col < COLS & row < ROWS; col++, row++){
        let run = [board[1][1]];
        console.log(run);
        // for(let row = 1; row < ROWS; row++){
        //     let current = board[row][col];
        //     let next = board[row + 1][col + 1];
        //     let currentGem = current.getData('key');
        //     let diagonals = findAntiDiagonals(scene,row);
        //     let antiDiagonals = findAntiDiagonals(scene,row);

        //     while (!isOutOfBounds(row,col,run)){
        //         diagonals.forEach((gem) => {
        //             if(gem.getData('key') == currentGem){
        //                 run.push(gem);
        //             } else {
        //                 if(run.length >= 3){
        //                     matches.push(run.slice());
        //                 }
        //             }
        //         })
        //         antiDiagonals.forEach((gem) => {
        //             if(gem.getData('key') == currentGem){
        //                 run.push(gem);
        //             } else {
        //                 if (run.length >= 3) {
        //                     matches.push(run.slice());
        //                 }
        //             }
        //         })
        //     } 
        //     if(run.length >= 3) {
        //         matches.push(run.slice());
        //     }
        //     run = [next];            
        // }
    }

    return matches;
}

function handleMatches(scene, matches) {
  const board = scene.board;
  const toRemove = new Set();

  // Collect all unique gems that should be removed
  matches.forEach((run) => {
    run.forEach((gem) => {
      if (gem) {
        toRemove.add(gem);
      }
    });
  });

  const matchedCount = toRemove.size;

  // Scoring based on total distinct gems removed in this event
  // Scoring based on total distinct gems removed in this event
  let scoreGained = 0;
  if (matchedCount >= 3) {
    scoreGained = 100 + (matchedCount - 3) * 100;
  }

  // 🔹 apply double-score buff from gold bar
  if (scene.doubleScoreActive) {
    scoreGained *= 2;
  }

  // 🔹 Update round score
  scene.score += scoreGained;
  scene.scoreText.setText("Score: " + scene.score);

  // 🔹 Update cumulative total score
  scene.totalScore += scoreGained;
  scene.totalScoreText.setText("Total: " + scene.totalScore);

  // 🔹 Update high score (session-only for now)
  if (scene.totalScore > scene.highScore) {
    scene.highScore = scene.totalScore;
    scene.highScoreText.setText("High: " + scene.highScore);
  }
  updateDomHeader(scene);

  // Floating score text at the average position of all matched gems
  if (matchedCount > 0 && scoreGained > 0) {
    let sumX = 0;
    let sumY = 0;
    toRemove.forEach((gem) => {
      sumX += gem.x;
      sumY += gem.y;
    });
    const cx = sumX / matchedCount;
    const cy = sumY / matchedCount;

    const floatText = scene.add
      .text(cx, cy, "+" + scoreGained, {
        fontSize: "40px",
        fill: "#ffff00",
        stroke: "#000000",
        strokeThickness: 7,
      })
      .setOrigin(0.5);

    scene.tweens.add({
      targets: floatText,
      y: cy - 40,
      alpha: 0,
      duration: 600,
      ease: "Cubic.easeOut",
      onComplete: () => floatText.destroy(),
    });
  }

  // Animate pops, then drop gems when ALL are done
  let destroyCount = 0;
  const gemsToDestroy = Array.from(toRemove);
  const totalToDestroy = gemsToDestroy.length;

  if (totalToDestroy === 0) {
    dropGems(scene);
    return;
  }

  gemsToDestroy.forEach((gem) => {
    const row = gem.getData("row");
    const col = gem.getData("col");

    // Clear board cell
    if (board[row][col] === gem) {
      board[row][col] = null;
    }

    // 🔹 Highlight/glow before pop
    gem.setTint(0x00ffff); // cyan-ish

    scene.tweens.add({
      targets: gem,
      alpha: { from: 1, to: 0.6 },
      duration: 100,
      yoyo: true,
      repeat: 0,
      onComplete: () => {
        // After quick glow, do the pop
        playPopAnimation(scene, gem, () => {
          destroyCount++;
          if (destroyCount === totalToDestroy) {
            dropGems(scene);
          }
        });
      },
    });
  });
}

function playPopAnimation(scene, gem, onComplete) {
  gem.setAngle(0);

  const baseScaleX = gem.scaleX;
  const baseScaleY = gem.scaleY;

  scene.tweens.add({
    targets: gem,
    scaleX: { from: baseScaleX, to: baseScaleX * 1.6 },
    scaleY: { from: baseScaleY, to: baseScaleY * 1.6 },
    alpha: { from: 1, to: 0 },
    angle: { from: 0, to: 120 },
    duration: 200,
    ease: "Back.easeOut",
    onComplete: () => {
      gem.destroy();
      if (onComplete) onComplete();
    },
  });
}

function dropGems(scene) {
  const board = scene.board;

  for (let col = 0; col < COLS; col++) {
    let emptyRow = ROWS - 1;

    // Shift existing gems downward
    for (let row = ROWS - 1; row >= 0; row--) {
      const gem = board[row][col];
      if (gem) {
        if (row !== emptyRow) {
          board[emptyRow][col] = gem;
          board[row][col] = null;

          gem.setData("row", emptyRow);

          const targetY = emptyRow * TILE_SIZE + TILE_SIZE / 2;
          const baseScaleY = gem.scaleY;

          scene.tweens.add({
            targets: gem,
            y: targetY,
            duration: 150,
            onComplete: () => {
              // small bounce when landing
              scene.tweens.add({
                targets: gem,
                scaleY: { from: baseScaleY * 1.2, to: baseScaleY },
                duration: 120,
                ease: "Bounce.easeOut",
              });
            },
          });
        }
        emptyRow--;
      }
    }

    // Fill remaining spaces with new gems
    for (let row = emptyRow; row >= 0; row--) {
      const newGem = createRandomGem(scene, row, col);
      board[row][col] = newGem;

      // spawn above and drop
      newGem.y = -TILE_SIZE;
      const targetY = row * TILE_SIZE + TILE_SIZE / 2;
      const baseScaleY = newGem.scaleY;

      scene.tweens.add({
        targets: newGem,
        y: targetY,
        duration: 200,
        onComplete: () => {
          // bounce for new gems too
          scene.tweens.add({
            targets: newGem,
            scaleY: { from: baseScaleY * 1.2, to: baseScaleY },
            duration: 120,
            ease: "Bounce.easeOut",
          });
        },
      });
    }
  }

  // 🔹 After drops are visually done, check for new combos
  scene.time.delayedCall(250, function () {
    const newMatches = findMatches(scene);
    if (newMatches.length > 0) {
      // This is your cascade: gems that dropped (or new ones) formed matches
      handleMatches(scene, newMatches);
    } else {
      // No more matches – the move is fully resolved
      checkRoundEnd(scene);
    }
  });
}

function checkRoundEnd(scene) {
  // Win: target reached
  if (scene.score >= scene.targetScore) {
    scene.round += 1;
    startNewRound(scene);
    return;
  }

  // Lose: out of moves and not enough score -> GAME OVER
  if (scene.movesLeft <= 0) {
    endGame(scene);
    return;
  }

  // Otherwise, continue playing this round
  scene.isProcessingMove = false;
}

function endGame(scene) {
  scene.isGameOver = true;
  scene.isProcessingMove = false;

  let sentData = "score=" + scene.totalScore + "&round=" + scene.round;
  let request = new XMLHttpRequest();
  request.open("POST", "../scoreReport.php");
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
    }
  };
  request.send(sentData);

  const w = scene.scale.width;
  const h = scene.scale.height;

  // Dark overlay
  const overlay = scene.add
    .rectangle(0, 0, w, h, 0x000000, 0.7)
    .setOrigin(0, 0);

  // Game Over text
  const gameOverText = scene.add
    .text(w / 2, h / 2 - 40, "GAME OVER", {
      fontSize: "48px",
      fill: "#ffffff",
      stroke: "#000000",
      strokeThickness: 6,
    })
    .setOrigin(0.5);

  // Show final total score (good for future DB storing)
  const scoreText = scene.add
    .text(w / 2, h / 2 + 5, "Total Score: " + scene.totalScore, {
      fontSize: "24px",
      fill: "#ffffaa",
      stroke: "#000000",
      strokeThickness: 3,
    })
    .setOrigin(0.5);

  const promptText = scene.add
    .text(w / 2, h / 2 + 45, "Click to play again", {
      fontSize: "20px",
      fill: "#ffffff",
    })
    .setOrigin(0.5);

  // One-time click to restart
  overlay.setInteractive();
  overlay.once("pointerdown", () => {
    overlay.destroy();
    gameOverText.destroy();
    scoreText.destroy();
    promptText.destroy();
    restartGame(scene);
  });
}

function restartGame(scene) {
  // Keep highScore, reset everything else
  scene.round = 1;
  scene.score = 0;
  scene.totalScore = 0;

  scene.movesLeft = MOVES_PER_ROUND;
  scene.targetScore = BASE_TARGET_SCORE;
  scene.isGameOver = false;

  startNewRound(scene);
}
