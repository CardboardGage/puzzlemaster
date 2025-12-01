var config = {
    type: Phaser.AUTO,
    height: GAME_HIEGHT,
    width: GAME_WIDTH,
    scene: { preload, create, update }
};

var game = new Phaser.Game(config);
