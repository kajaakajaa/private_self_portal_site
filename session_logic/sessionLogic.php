<?php

class sessionLogic {  
  //ログイン
  public function signIn() {
    session_start();
    session_regenerate_id();
    $rtn = false;
    //ログイン済みでsign_inページに訪れた場合
    if($_SESSION['user_name'] && $_SESSION['password'] || isset($_COOKIE['keep_session'])) {
      return $rtn = true;
    }
    else {
      //新しくログインする場合
      $_SESSION = array();
      //32ビット乱数を生成
      $token_byte = openssl_random_pseudo_bytes(32);
      //乱数をバイナリから文字列に変換
      $csrf_token = bin2hex($token_byte);
      //文字列乱数を暗号化
      $csrf_token = base64_encode($csrf_token);
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