var config = {
    type: Phaser.AUTO,
    // creatContainer: true,
    // behindCanvas: true,
    // PointerEvent: "gameArea",
    width: GAME_WIDTH,
    height: GAME_HIEGHT,
    scene: { preload, create, update }
};

var game = new Phaser.Game(config);
