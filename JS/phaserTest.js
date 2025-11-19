var config = {
    type: Phaser.AUTO,
    width: 800,
    height: 600,
    scene: {
        preload: preload,
        create: create,
        update: update
    }
};

var game = new Phaser.Game(config);

function preload ()
{ 
  this.load.image('gamegrid', '../assets/gamepieces/gamegrid.jpg')
  this.load.image('triangleGem','../assets/gamepieces/gamepeiece01.jpg');
  this.load.image('squareGem','../assets/gamepieces/gamepeiece02.jpg');
  this.load.image('diamondGem','../assets/gamepieces/gamepeiece03.jpg');
  this.load.image('hexagonGem','../assets/gamepieces/gamepeiece04.jpg');
  this.load.image('octogonGem','../assets/gamepieces/gamepeiece05.jpg');
}

function create ()
{
  this.add.image(400,300, 'gamegrid').setOrigin(0,0);
  this.add.image(100,100, 'triangleGem');
}

function update ()
{
}