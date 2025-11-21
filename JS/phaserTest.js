var config = {
    type: Phaser.AUTO,
    height: GAME_HIEGHT,
    scene: { preload, create, update }
};

var game = new Phaser.Game(config);
