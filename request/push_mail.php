<?php
include_once('/home/xs072153/kajaaserver.com/public_html/self_portal_site_private/config/db_connect.php');
include_once('/home/xs072153/kajaaserver.com/public_html/self_portal_site_private/config/console_log.php');

  $sql = <<<EOF
    SELECT
      user_name
    FROM
      tbl_task_user AS usr
        LEFT JOIN
          tbl_task_report AS rpt
            ON
          usr.no = rpt.user_no
    WHERE
      push_status = 1
        &&
      work_date = :work_date
EOF;
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':work_date', date('Y/m/d'), PDO::PARAM_STR);
  $stmt->execute();
  $array = array();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $array[] = $row;
  }
  mb_language("japanese");
  mb_internal_encoding("UTF-8");
  if($array != null) {
    foreach($array as $key => $value) {
      $to = $value['user_name'];
      $subject = "【TASK】本日の予定のお知らせ。";
      $message = $value['user_name'] . "さん。\n"
      . date('Y/m/d/') . "(本日)は予定が入っている様です。\n"
      . "詳細はマイページよりご確認下さい。";
      $headers = 'From: <info@kajaaserver.com>';
      mb_send_mail($to, $subject, $message, $headers);
    }
    //通知後チェックを外す
    $sql = <<<EOF
      UPDATE
        tbl_task_report
      SET
        push_status = 0
      WHERE
        work_date = :work_date
          &&
        push_status = 1
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':work_date', date('Y/m/d'), PDO::PARAM_STR);
    $result = $stmt->execute();
  }