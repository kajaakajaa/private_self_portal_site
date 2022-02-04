<?php
include_once('../../config/db_connect.php');

$mode = $_GET['mode'];
switch($mode) {
  case 'regist_user':
    $userName = $_POST['user_name'];
    $passWord = $_POST['password'];
    $sql = <<<EOF
      INSERT INTO
        tbl_task_user(
          user_name,
          password,
          regist_time
        ) VALUES (
          :user_name,
          :password,
          NOW()
        )
EOF
  break;
}