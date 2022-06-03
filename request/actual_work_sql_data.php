<?php

include_once('../config/db_connect.php');
include_once('../config/console_log.php');


$mode = $_GET['mode'];
switch($mode) {
  case 'regist_salary':
    $amount = $_POST['amount'];
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
    $userName = $_SESSION['user_name'];
    $passWord = $_SESSION['password'];
    $sql = <<<EOF
      SELECT
        rpt.salary AS salary,
        work_date AS work_month
      FROM
        tbl_task_user AS usr
          LEFT JOIN
            tbl_task_report AS rpt
          ON
            usr.no = rpt.user_no
      WHERE
        usr.user_name = :user_name
          AND
        usr.password = :password
          AND
        rpt.salary > 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $row['work_month'] = strtotime($row['work_month']);
      $row['work_month'] = date('n', $row['work_month']);
      $array['user'][] = $row;
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;
}