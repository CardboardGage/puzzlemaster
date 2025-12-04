var config = {
  type: Phaser.AUTO,
  width: 512, // 8 cols * 64 tile size (you can keep 800 if you want for now)
  height: 520,
  parent: "phaser-container", // 🔹 NEW: attach canvas inside .gameArea
  scene: { preload, create, update },
};

let menuButton = document.querySelector("#menuButton");
menuButton.addEventListener("click", () => {
  window.location.href = "../index.php";
});

var game = new Phaser.Game(config);
