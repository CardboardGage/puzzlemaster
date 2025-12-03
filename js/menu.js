$mainMenu = $(".mainMenu");
$backBtn = $("#backBtn");
$leaderboard = $("#leaderboard");

$("#startBtn").on("click", ()=>{
  window.location.href = "../screens/gameScreen.php";
});

$("#loginBtn").on("click", ()=>{
  window.location.href = "../login.php";
});

$("#configBtn").on("click", ()=>{
  $mainMenu.hide();
  $leaderboard.hide();
  $(".configMenu").show();
  $backBtn.show();
});

$("#maintBtn").on("click", ()=>{
  window.location.href = "../screens/maintenanceMenu.php";
});

$backBtn.on("click", ()=>{
  $mainMenu.show();
  $leaderboard.show();
  $(".configMenu").hide();
  $backBtn.hide();
});

$("#logoutBtn").on("click", () => {
  window.location.href = "../logout.php";
});