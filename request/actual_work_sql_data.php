<?php

include_once('../config/db_connect.php');
include_once('../config/console_log.php');


$mode = $_GET['mode'];
switch($mode) {
  case 'regist_salary':
    $amount = $_POST['amount'] == null ? NULL : $_POST['amount'];
    $userNo = $_POST['user_no'];
    $date = $_POST['work_date'];
    $sql = <<<EOF
      UPDATE
        tbl_task_report
      SET
        salary = :amount
      WHERE
        user_no = :user_no
          AND
        work_date = :work_date
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_STR);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $stmt->bindParam(':work_date', $date, PDO::PARAM_STR);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'set_list_acual_work':
    session_start();
    //sessionログインの場合
    $userName = $_SESSION['user_name'];
    $passWord = $_SESSION['password'];
    $Year = $_POST['year'];
    //cookieログインの場合
    $cookiePassword = isset($_COOKIE['keep_session']) ? $_COOKIE['keep_session'] : NULL;
    $sql = <<<EOF
      SELECT
        work_date AS work_month,
        TIME_FORMAT(
          work_time,
          '%H:%i'
        ) AS work_time,
        TIME_FORMAT(
          home_time,
          '%H:%i'
        ) AS home_time,
        salary
      FROM
        tbl_task_user AS usr
          LEFT JOIN
            tbl_task_report AS rpt
          ON
            usr.no = rpt.user_no
      WHERE
        (usr.user_name = :user_name
            &&
          usr.password = :password
            &&
          rpt.salary IS NOT NULL
            &&
          DATE_FORMAT(
            work_date,
            '%Y'
          ) = :year
            &&
          (work_time IS NOT NULL
              &&
            home_time IS NOT NULL
          )
        )
          ||
        cookie_pass = :cookiepass
          &&
        rpt.salary IS NOT NULL
          &&
        DATE_FORMAT(
          work_date,
          '%Y'
        ) = :year
          &&
        (work_time IS NOT NULL
            &&
          home_time IS NOT NULL
        )
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
    $stmt->bindParam(':password', $passWord, PDO::PARAM_STR);
    $stmt->bindParam(':cookiepass', $cookiePassword, PDO::PARAM_STR);
    $stmt->bindParam(':year', $Year, PDO::PARAM_INT);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row['work_month'] = strtotime($row['work_month']);
      $row['work_month'] = date('n', $row['work_month']);
      $array['user'][] = $row;
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
    exit;
  break;

  case 'detail_salary':
    session_start();
    $userName = $_SESSION['user_name'];
    $passWord = $_SESSION['password'];
    $Month = $_POST['month'];
    $cookiePassword = isset($_COOKIE['keep_session']) ? $_COOKIE['keep_session'] : NULL;
    $sql = <<<EOF
      SELECT
        TIME_FORMAT(
          work_time,
          '%H:%i'
        ) AS work_time,
        TIME_FORMAT(
          home_time,
          '%H:%i'
        ) AS home_time,
        DATE_FORMAT(
          work_date,
          '%e'
        ) AS work_day,
        work_date,
        salary
      FROM
        tbl_task_user AS usr
          LEFT JOIN
            tbl_task_report AS rpt
          ON
            usr.no = rpt.user_no
      WHERE
        (user_name = :user_name
            &&
          password = :password
            &&
          DATE_FORMAT(
            work_date,
            '%m'
          ) = :month
            &&
          home_time IS NOT NULL
              &&
          work_time IS NOT NULL
        )
          ||
        cookie_pass = :cookie_pass
          &&
        DATE_FORMAT(
          work_date,
          '%m'
        ) = :month
          &&
        home_time IS NOT NULL
          &&
        work_time IS NOT NULL
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
    $stmt->bindParam(':password', $passWord, PDO::PARAM_STR);
    $stmt->bindParam(':month', $Month, PDO::PARAM_INT);
    $stmt->bindParam(':cookie_pass', $cookiePassword, PDO::PARAM_STR);
    $stmt->execute();
    $array = array();
    //曜日取得
    $week = ['日','月','火','水','木','金','土'];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $weekNum = date('w', strtotime($row['work_date']));
      $row['work_date'] = '(' . $week[$weekNum] . ')';
      $array['user'][] = $row;
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
    exit;
  break;
}