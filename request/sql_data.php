<?php
include_once('../../config/db_connect.php');

$mode = $_GET['mode'];
switch($mode) {
  case 'work_time':
    $myNo = $_POST['my_no'];
    $workTime = $_POST['work_time'];
    if($workTime) {
      $sql = <<<EOF
        UPDATE
          tbl_work_time
        SET
          work_time = :work_time
        WHERE
          no = :my_no
            AND
          del_flg = 0
EOF;
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':work_time', $workTime, PDO::PARAM_STR);
      $stmt->bindParam(':my_no', $myNo, PDO::PARAM_INT);
      $stmt->execute();
      $array = array();
      $array = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    else {
      $sql = <<<EOF
        INSERT INTO
          tbl_work_time
        (
          work_time
        ) VALUES (
          :work_time
        )
EOF;
      $stmt = $
    }
  break;
}