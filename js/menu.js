// button initialization
$startBtn = $("#startBtn");
$loginBtn = $("#loginBtn");
$configBtn = $("#configBtn");
$maintBtn = $("#maintBtn");
$backBtn = $("#backBtn");
$registerBtn = $("#registerBtn");

$startBtn.on("click", ()=>{
  console.log("Start Pressed");
  $(".mainMenu").hide();
  $backBtn.show();
});

$loginBtn.on("click", ()=>{
  console.log("Login Pressed");
  $(".mainMenu").hide();
  $(".loginMenu").show();
  $backBtn.show();
});

$configBtn.on("click", ()=>{
  console.log("Config Pressed");
  $(".mainMenu").hide();
  $(".configMenu").show();
  $backBtn.show();
});

$maintBtn.on("click", ()=>{
  console.log("Maint Pressed");
  $(".mainMenu").hide();
  $backBtn.show();
});

$backBtn.on("click", ()=>{
  $(".mainMenu").show();
  $(".configMenu").hide();
  $(".loginMenu").hide();
  $backBtn.hide();
});


$registerBtn.on("click", ()=>{
  
});