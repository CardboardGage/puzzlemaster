// button initialization
$startBtn = $("#startBtn");
$loginBtn = $("#loginBtn");
$configBtn = $("#configBtn");
$maintBtn = $("#maintBtn");
$backBtn = $("#backBtn");

$startBtn.on("click", ()=>{
  console.log("Start Pressed");
  $(".mainMenu").hide();
  $backBtn.show();
});

$loginBtn.on("click", ()=>{
  console.log("Login Pressed");
  $(".mainMenu").hide();
  $backBtn.show();
  window.location.href = "../login.php";
});

$configBtn.on("click", ()=>{
  console.log("Config Pressed");
  $(".mainMenu").hide();
  $backBtn.show();
});

$maintBtn.on("click", ()=>{
  console.log("Maint Pressed");
  $(".mainMenu").hide();
  $backBtn.show();
});

$backBtn.on("click", ()=>{
  $(".mainMenu").show();
  $backBtn.hide();
});