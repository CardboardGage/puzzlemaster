// button initialization
$startBtn = $("#startBtn");
$loginBtn = $("#loginBtn");
$configBtn = $("#configBtn");
$maintBtn = $("#maintBtn");
$backBtn = $("#backBtn");
$registerBtn = $("#registerBtn");
$leaderboard = $("#leaderboard");

$startBtn.on("click", ()=>{
  console.log("Start Pressed");
  $(".mainMenu").hide();
  $leaderboard.hide();
  $(".startMenu").show();
  $backBtn.show();
  window.location.href = "../screens/gameScreen.php";
});

$loginBtn.on("click", ()=>{
  console.log("Login Pressed");
  $(".mainMenu").hide();
  $leaderboard.hide();
  $(".loginMenu").show();
  $(".startMenu").hide();
  // $backBtn.show();
  window.location.href = "../login.php";
});

$configBtn.on("click", ()=>{
  console.log("Config Pressed");
  $(".mainMenu").hide();
  $leaderboard.hide();
  $(".configMenu").show();
  $(".startMenu").hide();
  $backBtn.show();
});

$maintBtn.on("click", ()=>{
  console.log("Maint Pressed");
  $(".mainMenu").hide();
  $leaderboard.hide();
  window.location.href = "../screens/maintenanceMenu.php";
});

$backBtn.on("click", ()=>{
  $(".mainMenu").show();
  $leaderboard.show();
  $(".configMenu").hide();
  $(".loginMenu").hide();
  $(".startMenu").hide();
  $backBtn.hide();
});
