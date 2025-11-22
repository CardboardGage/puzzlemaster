$backBtn = $("#backBtn");
$return = $("#returnBtn");
$editUser = $("#editUserBtn");
$createRun = $("#createRunBtn");
$createMode = $("#createModeBtn");
$dataBtn = $(".dataBtn")

$editUser.on("click", ()=>{
  $dataBtn.hide();
  $("#userDataEditor").show();
  $return.hide()
  $backBtn.show();
});

$createRun.on("click", ()=>{
  $dataBtn.hide();
  $("#runDataGenerator").show();
  $return.hide()
  $backBtn.show();
});

$createMode.on("click", ()=>{
  $dataBtn.hide();
  $("#modeEditor").show();
  $return.hide()
  $backBtn.show();
});

$backBtn.on("click", ()=>{
  console.log("back button");
  window.location.href="maintenanceMenu.php";
});

$return.on("click", ()=>{
  window.location.href = "../index.php";
})