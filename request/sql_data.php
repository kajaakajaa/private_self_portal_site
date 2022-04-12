<?php
include_once('../config/db_connect.php');
include_once('../config/console_log.php');

$userNo = $_POST['user_no'];
$workDate = $_POST['work_date'];
$num = $_POST['num'];
$year = date('Y', strtotime($workDate));
$month = date('m', strtotime($workDate));
$day = date('d', strtotime($workDate));
$weekDay = array('日','月','火','水','木','金','土');
//$_POST送信の値を改行した状態でsqlへ保存(定義)
function sanitized($str) {
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}
$mode = $_GET['mode'];
switch($mode) {
  case 'set_list_shift':
    $workDate = $_POST['work_date'];
    $sql = <<<EOF
      SELECT
        no
      FROM
        tbl_task_user
      WHERE
        del_flg = 0
          AND
        status = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $array['user_no'][] = $row;
    }
    $sql = <<<EOF
      SELECT
        task,
        DATE_FORMAT
        (
          work_time,
          '%H:%i'
        ) AS work_time,
        DATE_FORMAT
        (
          home_time,
          '%H:%i'
        ) AS home_time
      FROM
        tbl_task_report
      WHERE
        work_date = :work_date
          AND
        del_flg = 0
          AND
        status = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row['task'] = sanitized($row['task'], false);
      $array['user'][] = $row;
    }
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
      INSERT
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
    $weekNum = date('w', strtotime($workDate));
    $sql = <<<EOF
    INSERT
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
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    $sql = <<<EOF
      SELECT
        task,
        work_date,
        DATE_FORMAT
        (
          work_time,
          '%H:%i'
        ) AS work_time,
        DATE_FORMAT
        (
          home_time,
          '%H:%i'
        ) AS home_time
      FROM
        tbl_task_report
      WHERE
        work_date = :work_date
          AND
        del_flg = 0
          AND
        status = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row['task'] = sanitized($row['task'], false);
      $row['work_date'] .= '(' . $weekDay[$weekNum] . ')';
      $array['user'][] = $row;
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'get_day':
    $timestamp = $num == 1 ? mktime(0,0,0,$month,$day-1,$year) : mktime(0,0,0,$month,$day+1,$year);
    //曜日取得
    $weekNum = date('w', $timestamp);
    $weekDay = '(' . $weekDay[$weekNum] . ')';
    //文字列の日付生成
    $date = date('Y-m-d', $timestamp);
    $sql = <<<EOF
      INSERT
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
        work_date = :date
          AND
        del_flg = 0
          AND
        status = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
    $array = array();
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    $array['work_date'] .= $weekDay;
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'edit_task':
    $userNo = $_POST['user_no'];
    $editTask = $_POST['edit_task'];
    $workDate = $_POST['work_date'];
    $sql = <<<EOF
      INSERT
        tbl_task_report
        (
          task,
          user_no,
          work_date,
          regist_time
        ) VALUES (
          :edit_task,
          :user_no,
          :work_date,
          NOW()
        ) ON DUPLICATE KEY UPDATE
          task = :edit_task,
          user_no = :user_no,
          work_date = :work_date,
          update_time = NOW()
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':edit_task', $editTask, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'reflect_task':
    $userNo = $_POST['user_no'];
    $workDate = $_POST['work_date'];
    $sql = <<<EOF
      SELECT
        task
      FROM
        tbl_task_report
      WHERE
        user_no = :user_no
          AND
        work_date = :work_date
          AND
        del_flg = 0
          AND
        status = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;
}