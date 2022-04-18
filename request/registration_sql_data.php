<?php
include_once('../config/db_connect.php');
include_once('../config/console_log.php');

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
$mode = $_GET['mode'];
switch($mode) {
  case 'regist_user':
    $userName = h($_POST['user_name']);
    $passWord = h($_POST['password']);
    $sql = <<<EOF
      INSERT INTO
        tbl_task_user
        (
          user_name,
          password,
          regist_time
        ) VALUES (
          :user_name,
          :password,
          NOW()
        )
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
    $stmt->bindParam(':password', $passWord, PDO::PARAM_STR);
    $result = $stmt->execute();
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($result);
  break;

  case 'login':
    session_start();
    $token = h(filter_input(INPUT_POST, 'token'));
    if($_SESSION['token'] === $token) {
      $userName = h(filter_input(INPUT_POST, 'user_name'));
      $passWord = h(filter_input(INPUT_POST, 'password'));
      $sql = <<<EOF
        SELECT
          user_name,
          password
        FROM
          tbl_task_user
        WHERE
          user_name = :user_name
            AND
          password = :password
EOF;
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
      $stmt->bindParam(':password', $passWord, PDO::PARAM_STR);
      $stmt->execute();
      $array = array();
      $array = $stmt->fetch(PDO::FETCH_ASSOC);
      $_SESSION['user_name'] = $array['user_name'];
      $_SESSION['password'] = $array['password'];
      header('Content-type: application/json; charset=UTF-8');
      echo json_encode($array);
    }
    else {
      header('Content-type: application/json; charset=UTF-8');
      echo json_encode(false);
    }
  break;
}