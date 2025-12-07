$backBtn = $("#backBtn");
$return = $("#returnBtn");
$editUser = $("#editUserBtn");
$createRun = $("#createRunBtn");
$createMode = $("#createModeBtn");
$dataBtn = $(".dataBtn");

$editUser.on("click", ()=>{
  $dataBtn.hide();
  $("#userDataEditor").show();
  $backBtn.show();
});

$createRun.on("click", ()=>{
  $dataBtn.hide();
  $("#runDataGenerator").show();
  $backBtn.show();
});

$createMode.on("click", ()=>{
  $dataBtn.hide();
  $("#modeEditor").show();
  $backBtn.show();
});

$backBtn.on("click", ()=>{
  $("#userDataEditor").hide();
  $("#runDataGenerator").hide();
  $("#modeEditor").hide();
  $dataBtn.show();
  $backBtn.hide();
  window.location.href = "maintenanceMenu.php";
});

$return.on("click", ()=>{
  window.location.href = "../index.php";
})
