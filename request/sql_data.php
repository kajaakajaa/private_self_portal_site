<?php
include_once('../../config/db_connect.php');

$mode = $_GET['mode'];
switch($mode) {
  case 'set_list_shift':
    $workDate = $_POST['work_date'];
    $sql = <<<EOF
      SELECT
        no,
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
        tbl_work_time
      WHERE
        work_date = :work_date
          AND
        del_flg = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        tbl_work_time
        (
          no,
          work_time,
          work_date,
          regist_time
        ) VALUES (
          :user_no,
          :work_time,
          :work_date,
          NOW()
          ) ON DUPLICATE KEY UPDATE
          no = :user_no,
          work_time = :work_time,
          work_date = :work_date,
          update_time = NOW()
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':work_time', $workTime, PDO::PARAM_STR);
    $stmt->bindParam('work_date', $workDate, PDO::PARAM_STR);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'home_time':
    $userNo = $_POST['user_no'];
    $homeTime = $_POST['home_time'];
    $workDate = $_POST['work_date'];
    $sql = <<<EOF
      INSERT
        tbl_work_time
        (
          no,
          home_time,
          work_date,
          regist_time
        ) VALUES (
          :user_no,
          :home_time,
          :work_date,
          NOW()
        ) ON DUPLICATE KEY UPDATE
          no = :user_no,
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
    $workDate = $_POST['work_date'];
    $userNo = $_POST['user_no'];
    $sql = <<<EOF
      SELECT
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
        tbl_work_time
      WHERE
        work_date = :work_date
          AND
        no = :user_no
          AND
        del_flg = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':work_date', $workDate, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $array['user'][] = $row;
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'get_before_after_day':
    $workDate = $_POST['work_date'];
    $userNo = $_POST['user_no'];
    $otherDay = $_POST['other_day'];
    $year = date('Y', strtotime($workDate));
    $month = date('m', strtotime($workDate));
    $day = date('d', strtotime($workDate));
    $otherDay == 1 ? $timestamp = mktime(0,0,0,$month,$day-1,$year) : $timestamp = mktime(0,0,0,$month,$day+1,$year);
    $date = date('Y/m/j', $timestamp);
    $sql = <<<EOF
      SELECT
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
        tbl_work_time
      WHERE
        no = :user_no
          AND
        work_date = :date
          AND
        del_flg = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $array['user'][] = $row;
    }
    $array['date'] = $date;
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;
}