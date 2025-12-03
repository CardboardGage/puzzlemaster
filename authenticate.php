<?php 
if (!session_id()) {
  session_start();
}

if ($_SESSION['admin'] == false) {
  header('Location: /index.php');
  exit();
}
?>