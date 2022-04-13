<?php
include_once('../request/registration_sql_data.php');
include_once('../config/console_log.php');

session_start();

class sessionLogic {  
  //ログイン
  public function signIn() {
    $rtn = false;
    if($_SESSION['user_name'] && $_SESSION['password']) {
      return $rtn = true;
    }
    else {
      return $rtn;
    }
  }
  //ログアウト
  public function signOut() {
    $rtn = false;
    if($_SESSION['user_name'] && $_SESSION['password']) {
      $_SESSION = array();
      session_destroy();
      return $rtn = true;
    }
    else {
      return $rtn;
    }
  }
}
$logic = new sessionLogic();