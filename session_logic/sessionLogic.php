<?php

class sessionLogic {  
  //ログイン
  public function signIn() {
    session_start();
    session_regenerate_id();
    $rtn = false;
    //ログイン済みでsign_inページに訪れた場合
    if($_SESSION['user_name'] && $_SESSION['password']) {
      return $rtn = true;
    }
    else {
      //新しくログインする場合
      $_SESSION = array();
      $token_byte = openssl_random_pseudo_bytes(16);
      $csrf_token = bin2hex($token_byte);
      $_SESSION['token'] = $csrf_token;
      return $rtn;
    }
  }
  //ログアウト
  public function signOut() {
    session_start();
    $rtn = false;
    if($_SESSION['user_name'] && $_SESSION['password'] || isset($_COOKIE['keep_session'])) {
      $_SESSION = array();
      setcookie(session_name(), '', time()-1000, '/');
      setcookie('keep_session', '', time()-1000, '/');
      session_destroy();
      return $rtn = true;
    }
    else {
      setcookie(session_name(), '', time()-1000, '/');
      return $rtn;
    }
  }
}
$logic = new sessionLogic();