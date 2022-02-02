<?php
// セッションの開始
function session_idset() {
  session_start();
  $_SESSION['auth'] = session_id();
}

// セッションのチェック
function session_check() {
  session_start();
  $rtn = TRUE;
  if($_SESSION['auth']) != session_id()) {
    $rtn = FALSE;
  }
  return $rtn;
}