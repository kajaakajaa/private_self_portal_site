<?php
include_once('../../config/db_connect.php');

$mode = $_GET['mode'];
switch($mode) {
  case 'set_list_shift':
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
        del_flg = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $array['user'][] = $row;
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'work_time':
    $userNo = $_POST['user_no'];
    $workTime = $_POST['work_time'];
    $sql = <<<EOF
      UPDATE
        tbl_work_time
      SET
        work_time = :work_time,
        update_time = NOW()
      WHERE
        no = :user_no
          AND
        del_flg = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':work_time', $workTime, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'home_time':
    $userNo = $_POST['user_no'];
    $homeTime = $_POST['home_time'];
    $sql = <<<EOF
      UPDATE
        tbl_work_time
      SET
        home_time = :home_time,
        update_time = NOW()
      WHERE
        no = :user_no
          AND
        del_flg = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':home_time', $homeTime, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;
}