<?php
include_once('../request/registration_sql_data.php');
include_once('../config/console_log.php');

class sessionLogic {  
  //ログイン
  public function signIn() {
    session_start();
    $rtn = false;
    if($_SESSION['user_name'] && $_SESSION['password']) {
      return $rtn = true;
    }
    else {
      $_SESSION = array();
      return $rtn;
    }
  }
  //ログアウト
  public function signOut() {
    session_start();
    $rtn = false;
    if($_SESSION['user_name'] && $_SESSION['password']) {
      $_SESSION = array();
      if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-1000, '/');
      }
      session_destroy();
      return $rtn = true;
    }
    else {
      $_SESSION = array();
      setcookie(session_name(), '', time()-1000, '/');
      session_destroy();
      return $rtn;
    }
  }
}
$logic = new sessionLogic();