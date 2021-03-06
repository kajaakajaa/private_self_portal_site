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
    $passWord = password_hash(h($_POST['password']), PASSWORD_DEFAULT);
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
    $array = array();
    $array['result'] = $stmt->execute();
    $sql = <<<EOF
      SELECT
        user_name
      FROM
        tbl_task_user
      WHERE
        user_name = :user_name
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
    $stmt->execute();
    $array['user_name'] = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
    exit;
  break;

  case 'sign_in':
    session_start();
    $token = h(filter_input(INPUT_POST, 'token'));
    $status = filter_input(INPUT_POST, 'status');
    if($_SESSION['token'] == $token) {
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
          del_flg = 0
EOF;
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
      $stmt->execute();
      $array = array();
      $array = $stmt->fetch(PDO::FETCH_ASSOC);
      $password_verify = password_verify($passWord ,$array['password']);
      if($array == '') {
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode(false);
        exit;
        die();
      }
      //ユーザー名が正しくて、且つログイン維持にチェックが入っている場合
      if($status == 'true' && $array != '' && $password_verify == 'true') {
        $_SESSION['user_name'] = $array['user_name'];
        $_SESSION['password'] = $array['password'];
        //下記読み込まれた時点からカウント開始
        session_regenerate_id();
        setcookie('keep_session', session_id(), time()+259200, '/', '', true);
        $sql = <<<EOF
          UPDATE
            tbl_task_user
          SET
            cookie_pass = :cookie_pass
          WHERE
            !EXISTS
            (
              SELECT
                *
              FROM
                tbl_task_user
              WHERE
                cookie_pass = :cookie_pass
            )
            AND
          user_name = :user_name
EOF;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':user_name', $array['user_name'], PDO::PARAM_STR);
        $stmt->bindParam(':cookie_pass', session_id(), PDO::PARAM_STR);
        $stmt->execute();
        //↑の時点で重複、非重複関わらずアップデート出来てしまうので↓で判定する
        if($stmt->rowCount() > 0) {
          $array['result'] = '重複なし保存成功';
          header('Content-type: application/json; charset=UTF-8');
          echo json_encode($array);
          exit;
        }
        else {
          //確率はものすごい低いが、session_regenerate_id()↑の乱数生成で既に別のユーザーに使われている場合は登録されずに↓で再生成し、重複しないsession_idが発行されるまで繰り返す
          while($stmt->rowCount() == 0) {
            session_regenerate_id();
            setcookie('keep_session', session_id(), time()+259200, '/', '', true);
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':user_name', $array['user_name'], PDO::PARAM_STR);
            $stmt->bindParam(':cookie_pass', session_id(), PDO::PARAM_STR);
            $stmt->execute();
            $array['result'] = '重複処理成功';
          }
          header('Content-type: application/json; charset=UTF-8');
          echo json_encode($array);
          exit;
        }
      }
      else if($status == 'false' && $array != '' && $password_verify == 'true') {
        $_SESSION['user_name'] = $array['user_name'];
        $_SESSION['password'] = $array['password'];
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($array);
      }
      else {
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode(false);
      }
    }
    else {
      header('Content-type: application/json; charset=UTF-8');
      echo json_encode(false);
      exit;
      die();
    }
  break;

  case 'sign_out':
    $userNo = $_POST['user_no'];
    $sql = <<<EOF
      UPDATE
        tbl_task_user
      SET
        cookie_pass = NULL
      WHERE
        no = :user_no
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;
}