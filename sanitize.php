<?php

function sanitizeString($type, $field) {
  
  if ($type == INPUT_POST) {
    if (isset($_POST[$field])) {
      return htmlspecialchars(strip_tags($_POST[$field]));
    }
  } else if ($type == INPUT_GET) {
    if (isset($_GET[$field])) {
      return htmlspecialchars(strip_tags($_GET[$field]));
    }
  } else if ($type == "SESSION") {
    if (isset($_SESSION[$field])) {
      return htmlspecialchars(strip_tags($_SESSION[$field]));
    }
  } else {
    if (isset($_SERVER[$field])) {
      return htmlspecialchars(strip_tags($_SERVER[$field]));
    }
  } 
}

function sanitizeFloat($type, $field) {
  if ($type == INPUT_POST) {
    if (isset($_POST[$field])) {
      return filter_input(INPUT_POST, $field, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
  } else if ($type == INPUT_GET) {
    if (isset($_GET[$field])) {
      return filter_input(INPUT_GET, $field, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
  }
}

function sanitizeInt($type, $field) {
  if ($type == INPUT_POST) {
    if (isset($_POST[$field])) {
      return filter_input(INPUT_POST, $field, FILTER_SANITIZE_NUMBER_INT);
    }
  } else if ($type == INPUT_GET) {
    if (isset($_GET[$field])) {
      return filter_input(INPUT_GET, $field, FILTER_SANITIZE_NUMBER_INT);
    }
  }
}