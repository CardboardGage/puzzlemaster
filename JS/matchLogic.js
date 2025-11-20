
const ROWS = 8;
const COLS = 8;
const TILE_SIZE = 64;   // adjust to fit your background
const GAME_WIDTH = COLS * TILE_SIZE;
const GAME_HIEGHT = ROWS * TILE_SIZE;

// Round / Score settings
const MOVES_PER_ROUND   = 10;   // moves allowed per round
const BASE_TARGET_SCORE = 500;  // target score for round 1
const TARGET_INCREMENT  = 250;  // how much the target increases each round

// Gem keys used in the sprites
const GEM_KEYS = [
    'triangleGem',
    'squareGem',
    'diamondGem',
    'hexagonGem',
    'octogonGem'
];

// PHASER LIFECYCLE
//  PHASER LIFECYCLE
function preload() {
    // Background board image
    this.load.image('gamegrid', '../assets/gamepieces/gamegrid.jpg');

    // Gem images
    this.load.image('triangleGem', '../assets/gamepieces/gamepiece01.jpg');
    this.load.image('squareGem',   '../assets/gamepieces/gamepiece02.jpg');
    this.load.image('diamondGem',  '../assets/gamepieces/gamepiece03.jpg');
    this.load.image('hexagonGem',  '../assets/gamepieces/gamepiece04.jpg');
    this.load.image('octogonGem',  '../assets/gamepieces/gamepiece05.jpg');
}

function create() {
    // Draw background and size it to match the grid area
    this.add.image(0, 0, 'gamegrid')
        .setOrigin(0, 0)
        .setDisplaySize(COLS * TILE_SIZE, ROWS * TILE_SIZE);

    // Game state
    this.round            = 1;
    this.score            = 0;
    this.movesLeft        = MOVES_PER_ROUND;
    this.targetScore      = BASE_TARGET_SCORE;
    this.isProcessingMove = false;  // to prevent spam clicks during animations

    // Board and selection
    this.board        = [];
    this.selectedGem  = null;

    // UI Text (below the grid)
    this.scoreText = this.add.text(
        20,
        ROWS * TILE_SIZE + 10,
        'Score: 0',
        { fontSize: '20px', fill: '#ffffff' }
    );

    this.roundText = this.add.text(
        20,
        ROWS * TILE_SIZE + 35,
        'Round: ' + this.round,
        { fontSize: '20px', fill: '#ffffff' }
    );

    this.targetText = this.add.text(
        220,
        ROWS * TILE_SIZE + 10,
        'Target: ' + this.targetScore,
        { fontSize: '20px', fill: '#ffffff' }
    );

    this.movesText = this.add.text(
        220,
        ROWS * TILE_SIZE + 35,
        'Moves: ' + this.movesLeft,
        { fontSize: '20px', fill: '#ffffff' }
    );

    // Build the initial board
    startNewRound(this);

    // Input
    this.input.on('gameobjectdown', handleGemDown, this);
}

function update() {
    // Nothing needed per frame right now
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
            const gem = createRandomGem(scene, row, col);
            scene.board[row][col] = gem;
        }
    }

    // Reset round-based values
    scene.score       = 0;
    scene.movesLeft   = MOVES_PER_ROUND;
    scene.targetScore = BASE_TARGET_SCORE + (scene.round - 1) * TARGET_INCREMENT;
    scene.selectedGem = null;
    scene.isProcessingMove = false;

    // Update text UI
    scene.scoreText.setText('Score: ' + scene.score);
    scene.roundText.setText('Round: ' + scene.round);
    scene.targetText.setText('Target: ' + scene.targetScore);
    scene.movesText.setText('Moves: ' + scene.movesLeft);
}


function createRandomGem(scene, row, col) {
    const key = Phaser.Utils.Array.GetRandom(GEM_KEYS);

    const x = col * TILE_SIZE + TILE_SIZE / 2;
    const y = row * TILE_SIZE + TILE_SIZE / 2;

    const gem = scene.add.image(x, y, key);

    // Make sure gem fits in one grid cell
    gem.setDisplaySize(TILE_SIZE, TILE_SIZE);

    gem.setData('row', row);
    gem.setData('col', col);
    gem.setData('key', key);
    gem.setInteractive();

    return gem;
}

function handleGemDown(pointer, gem) {
    const scene = this;

    // Don't let user click during animations / resolving
    if (scene.isProcessingMove) return;

    // No moves left -> wait until round resets
    if (scene.movesLeft <= 0) return;

    // No gem selected yet: select this one
    if (!scene.selectedGem) {
        scene.selectedGem = gem;
        gem.setTint(0xffff00); // highlight
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

    const r1 = g1.getData('row');
    const c1 = g1.getData('col');
    const r2 = g2.getData('row');
    const c2 = g2.getData('col');

    const isAdjacent =
        (r1 === r2 && Math.abs(c1 - c2) === 1) ||
        (c1 === c2 && Math.abs(r1 - r2) === 1);

    if (!isAdjacent) {
        shakeGem(scene, gem);
        return;
    }

    // Use up one move
    scene.movesLeft--;
    scene.movesText.setText('Moves: ' + scene.movesLeft);

    // Lock input during swap + resolution
    scene.isProcessingMove = true;

    swapGems(scene, g1, g2, false);
}

// shake animation for invalid/bad interactions
function shakeGem(scene, gem) {
    scene.tweens.add({
        targets: gem,
        x: gem.x + 5,
        duration: 40,
        yoyo: true,
        repeat: 3
    });
}


function swapGems(scene, g1, g2, isReversing) {
    const r1 = g1.getData('row');
    const c1 = g1.getData('col');
    const r2 = g2.getData('row');
    const c2 = g2.getData('col');

    // Swap in the logical board array
    scene.board[r1][c1] = g2;
    scene.board[r2][c2] = g1;

    // Swap stored row/col values
    g1.setData('row', r2);
    g1.setData('col', c2);
    g2.setData('row', r1);
    g2.setData('col', c1);

    const g1TargetX = g1.getData('col') * TILE_SIZE + TILE_SIZE / 2;
    const g1TargetY = g1.getData('row') * TILE_SIZE + TILE_SIZE / 2;
    const g2TargetX = g2.getData('col') * TILE_SIZE + TILE_SIZE / 2;
    const g2TargetY = g2.getData('row') * TILE_SIZE + TILE_SIZE / 2;

    // Animate the swap
    scene.tweens.add({
        targets: g1,
        x: g1TargetX,
        y: g1TargetY,
        duration: 150
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
        }
    });
}

function findMatches(scene) {
    const board = scene.board;
    const matches = [];

    // Horizontal matches
    for (let row = 0; row < ROWS; row++) {
        let run = [board[row][0]];
        for (let col = 1; col < COLS; col++) {
            const current  = board[row][col];
            const previous = board[row][col - 1];

            if (current && previous && current.getData('key') === previous.getData('key')) {
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
            const current  = board[row][col];
            const previous = board[row - 1][col];

            if (current && previous && current.getData('key') === previous.getData('key')) {
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

    return matches;
}

function handleMatches(scene, matches) {
    const board = scene.board;
    const toRemove = new Set();

    // Collect all unique gems that should be removed
    matches.forEach(run => {
        run.forEach(gem => {
            if (gem) {
                toRemove.add(gem);
            }
        });
    });

    const matchedCount = toRemove.size;

    // Scoring based on total distinct gems removed in this event
    let scoreGained = 0;
    if (matchedCount >= 3) {
        // 3 => 100, 4 => 200, 5 => 300, etc.
        scoreGained = 100 + (matchedCount - 3) * 100;
    }

    // Update total score and UI
    scene.score += scoreGained;
    scene.scoreText.setText('Score: ' + scene.score);

    // Floating score text at the average position of all matched gems
    if (matchedCount > 0 && scoreGained > 0) {
        let sumX = 0;
        let sumY = 0;
        toRemove.forEach(gem => {
            sumX += gem.x;
            sumY += gem.y;
        });
        const cx = sumX / matchedCount;
        const cy = sumY / matchedCount;

        const floatText = scene.add.text(
            cx,
            cy,
            '+' + scoreGained,
            { fontSize: '24px', fill: '#ffff00', stroke: '#000000', strokeThickness: 3 }
        ).setOrigin(0.5);

        scene.tweens.add({
            targets: floatText,
            y: cy - 40,
            alpha: 0,
            duration: 600,
            ease: 'Cubic.easeOut',
            onComplete: () => floatText.destroy()
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

    gemsToDestroy.forEach(gem => {
        const row = gem.getData('row');
        const col = gem.getData('col');

        // Clear board cell
        if (board[row][col] === gem) {
            board[row][col] = null;
        }

        // Pop animation
        playPopAnimation(scene, gem, () => {
            destroyCount++;
            if (destroyCount === totalToDestroy) {
                dropGems(scene);
            }
        });
    });
}


function playPopAnimation(scene, gem, onComplete) {
    scene.tweens.add({
        targets: gem,
        scale: { from: 1, to: 1.4 },
        alpha: { from: 1, to: 0 },
        duration: 150,
        ease: 'Cubic.easeOut',
        onComplete: () => {
            gem.destroy();
            if (onComplete) onComplete();
        }
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

                    gem.setData('row', emptyRow);

                    const targetY = emptyRow * TILE_SIZE + TILE_SIZE / 2;
                    scene.tweens.add({
                        targets: gem,
                        y: targetY,
                        duration: 150
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
            scene.tweens.add({
                targets: newGem,
                y: targetY,
                duration: 200
            });
        }
    }

    // After drops, check for cascades
    scene.time.delayedCall(250, function () {
        const newMatches = findMatches(scene);
        if (newMatches.length > 0) {
            handleMatches(scene, newMatches);
        } else {
            // No more cascades: move fully resolved
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

    // Lose: out of moves and not enough score
    if (scene.movesLeft <= 0) {
        // Restart same round
        startNewRound(scene);
        return;
    }

    // Otherwise, continue playing this round
    scene.isProcessingMove = false;
}

