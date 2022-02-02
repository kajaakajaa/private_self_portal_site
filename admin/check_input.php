<?php
function check_input($username, $password, $last_name, $first_name) {
  $messages = array();

  // パスワードのチェック
  if($username !== NULL) {
    if($password == '') {
      array_push($messages, 'パスワードを入力して下さい。');
    }
    else {
      if(!mb_eregi('^[a-zA-Z0-9]{4,8}$', $password)) {
        array_push($messages, 'パスワードは4文字以上、8文字以下の英数字で入力して下さい。');
      }
    }
  }

  // 姓のチェック
  if($username !== NULL) {
    if($last_name == '') {
      array_push($messages, '姓を入力して下さい。');
    }
    else {
      if(strlen($last_name) > 50) {
        array_push($messages, '姓は50文字以下で入力して下さい。');
      }
    }
  }
  return $messages; //エラーメッセージが格納された配列を返却する
}