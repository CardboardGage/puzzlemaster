// button initialization
$startBtn = $("#startBtn");
$loginBtn = $("#loginBtn");
$configBtn = $("#configBtn");
$maintBtn = $("#maintBtn");
$backBtn = $(".backBtn");
$registerBtn = $("#registerBtn");

$startBtn.on("click", ()=>{
  console.log("Start Pressed");
  $(".mainMenu").hide();
  $(".startMenu").show();
  $backBtn.show();
});

$loginBtn.on("click", ()=>{
  console.log("Login Pressed");
  $(".mainMenu").hide();
  $(".loginMenu").show();
  $(".startMenu").hide();
  $backBtn.show();
});

$configBtn.on("click", ()=>{
  console.log("Config Pressed");
  $(".mainMenu").hide();
  $(".configMenu").show();
  $(".startMenu").hide();
  $backBtn.show();
});

$maintBtn.on("click", ()=>{
  console.log("Maint Pressed");
  $(".mainMenu").hide();
  $(".startMenu").show();
  $backBtn.show();
});

$backBtn.on("click", ()=>{
  $(".mainMenu").show();
  $(".configMenu").hide();
  $(".loginMenu").hide();
  $(".startMenu").hide();
  $backBtn.hide();
});
