<?php
// include_once('../request/registration_sql_data.php');
// include_once('../config/console_log.php');

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
    if($_SESSION['user_name'] && $_SESSION['password']) {
      $_SESSION = array();
      if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-1000, '/');
      }
      session_destroy();
      return $rtn = true;
    }
    else {
      setcookie(session_name(), '', time()-1000, '/');
      return $rtn;
    }
  }
  //ログイン維持
  function keep_sign_in() {
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 1);
    //ブラウザを閉じても稼働する秒数(第二引数)
    ini_set('session.cookie_lifetime', 60*60*24*3);
    //セッションが切れるまでの秒数(第二引数。※何もしてない状況が〇〇秒数続いた後リロードした際に)
    ini_set('session.gc_maxlifetime', 60*60*24*3);
  }
}
$logic = new sessionLogic();