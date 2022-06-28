<?php
include_once('../config/db_connect.php');
include_once('../config/console_log.php');

//$_POST送信の値を改行した状態でsqlへ保存(定義)
function sanitized($str) {
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}

$mode = $_GET['mode'];
switch($mode) {
  case 'set_list_shift':
    session_start();
    $workDate = $_POST['work_date'];
    if($_SESSION != null) {
      $sql = <<<EOF
        SELECT
          no
        FROM
          tbl_task_user
        WHERE
          user_name = :user_name
            AND
          password = :password
EOF;
      $userName = $_SESSION['user_name'];
      $passWord = $_SESSION['password'];
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
      $stmt->bindParam(':password', $passWord, PDO::PARAM_STR);
      $stmt->execute();
      $array = array();
      $array['user_no'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    else if(isset($_POST['user_no'])) {
      $array = array();
      $array['user_no']['no'] = $_POST['user_no'];
    }
    $sql = <<<EOF
      SELECT
        TIME_FORMAT
        (
          work_time,
          '%H:%i'
        ) AS work_time,
        TIME_FORMAT
        (
          home_time,
          '%H:%i'
        ) AS home_time
      FROM
        tbl_task_report
      WHERE
        user_no = :user_no
          AND
        work_date = :work_date
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $array['user_no']['no'], PDO::PARAM_INT);
    $stmt->execute();
    $array['user'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $sql = <<<EOF
      SELECT
        schedule
      FROM
        tbl_task_schedule
      WHERE
        user_no = :user_no
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $array['user_no']['no'], PDO::PARAM_INT);
    $stmt->execute();
    $array['task'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $array['task']['schedule'] = sanitized($array['task']['schedule'], false);
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'work_time':
    $userNo = $_POST['user_no'];
    $workTime = $_POST['work_time'];
    $workDate = $_POST['work_date'];
    $sql = <<<EOF
      INSERT INTO
        tbl_task_report
        (
          user_no,
          work_time,
          work_date,
          regist_time
        ) VALUES (
          :user_no,
          :work_time,
          :work_date,
          NOW()
          ) ON DUPLICATE KEY UPDATE
          user_no = :user_no,
          work_time = :work_time,
          work_date = :work_date,
          update_time = NOW()
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':work_time', $workTime, PDO::PARAM_STR);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'home_time':
    $userNo = $_POST['user_no'];
    $homeTime = $_POST['home_time'];
    $workDate = $_POST['work_date'];
    $sql = <<<EOF
      INSERT INTO
        tbl_task_report
        (
          user_no,
          home_time,
          work_date,
          regist_time
        ) VALUES (
          :user_no,
          :home_time,
          :work_date,
          NOW()
        ) ON DUPLICATE KEY UPDATE
          user_no = :user_no,
          home_time = :home_time,
          work_date = :work_date,
          update_time = NOW()
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':home_time', $homeTime, PDO::PARAM_STR);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'get_data':
    $userNo = $_POST['user_no'];
    $workDate = $_POST['work_date'];
    $weekDay = array('日','月','火','水','木','金','土');
    $weekNum = date('w', strtotime($workDate));
    $sql = <<<EOF
      INSERT INTO
        tbl_task_report
        (
          work_date,
          user_no,
          regist_time
        ) VALUES (
          :work_date,
          :user_no,
          NOW()
        ) ON DUPLICATE KEY UPDATE
          work_date = :work_date,
          user_no = :user_no,
          update_time = NOW()
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    $sql = <<<EOF
      SELECT
        work_date,
        TIME_FORMAT
        (
          work_time,
          '%H:%i'
        ) AS work_time,
        TIME_FORMAT
        (
          home_time,
          '%H:%i'
        ) AS home_time
      FROM
        tbl_task_report
      WHERE
        user_no = :user_no
          AND
        work_date = :work_date
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    $array = array();
    $array['user_data'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $array['user_data']['work_date'] .= '(' . $weekDay[$weekNum] . ')';
    $sql = <<<EOF
      SELECT
        schedule
      FROM
        tbl_task_schedule
      WHERE
        user_no = :user_no
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    $array['task'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $array['task']['schedule'] = sanitized($array['task']['schedule'], false);
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'get_day':
    $num = $_POST['num'];
    $userNo = $_POST['user_no'];
    $Today = $_POST['work_date'];
    $year = date('Y', strtotime($Today));
    $month = date('m', strtotime($Today));
    $day = date('d', strtotime($Today));
    $weekDay = array('日','月','火','水','木','金','土');
    $timestamp = $num == 1 ? mktime(0,0,0,$month,$day-1,$year) : mktime(0,0,0,$month,$day+1,$year);
    //曜日取得
    $weekNum = date('w', $timestamp);
    //文字列の日付生成
    $date = date('Y-m-d', $timestamp);
    $sql = <<<EOF
      INSERT INTO
        tbl_task_report
        (
          work_date,
          user_no,
          regist_time
        ) VALUES (
          :date,
          :user_no,
          NOW()
        ) ON DUPLICATE KEY UPDATE
          work_date = :date,
          user_no = :user_no,
          update_time = NOW()
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    $sql = <<<EOF
      SELECT
        work_date
      FROM
        tbl_task_report
      WHERE
        user_no = :user_no
          AND
        work_date = :date
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    $array = array();
    $array['work_date'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $array['work_date']['work_date'] .= '(' . $weekDay[$weekNum] . ')';
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'edit_task':
    $userNo = $_POST['user_no'];
    $editTask = $_POST['edit_task'];
    $sql = <<<EOF
      INSERT INTO
        tbl_task_schedule
        (
          schedule,
          user_no,
          regist_time
        ) VALUES (
          :edit_task,
          :user_no,
          NOW()
        ) ON DUPLICATE KEY UPDATE
          schedule = :edit_task,
          user_no = :user_no,
          update_time = NOW()
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':edit_task', $editTask, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'reflect_task':
    $userNo = $_POST['user_no'];
    $sql = <<<EOF
      SELECT
        schedule
      FROM
        tbl_task_schedule
      WHERE
        user_no = :user_no
          AND
        del_flg = 0
          AND
        status = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'schedule_date':
    $userNo = $_POST['user_no'];
    $workDate = $_POST['work_date'];
    $pushStatus = $_POST['scheduleDate'] == "true" ? 1 : 0;
    $sql = <<<EOF
      INSERT INTO
        tbl_task_report
        (
          user_no,
          work_date,
          push_status,
          regist_time
        ) VALUES (
          :user_no,
          :work_date,
          :push_status,
          NOW()
        ) ON DUPLICATE KEY UPDATE
          user_no = :user_no,
          work_date = :work_date,
          push_status = :push_status,
          update_time = NOW()
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->bindParam(':push_status', $pushStatus, PDO::PARAM_INT);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'push_set':
    $userNo = $_POST['user_no'];
    $workDate = $_POST['work_date'];
    $sql = <<<EOF
    SELECT
      push_status
    FROM
      tbl_task_report
    WHERE
      user_no = :user_no
        &&
      work_date = :work_date
        &&
      push_status = 1
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    $array = array();
    $array['push_check'] = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;
}