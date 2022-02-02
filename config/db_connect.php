<?php
function db_connect() {
  $dns = 'mysql6b.xserver.jp';
  $user = 'fivelink_arakawa';
  $password = 'O41GC2Ve';
  $dbname = 'fivelink_arakawa';
  try {
    $dbh = new PDO('mysql:host=' . $dns . ';dbname=' . $dbname . ';charset=utf8', $user, $password);
    return $dbh;
  } catch (PDOException $e) {
    message_out('データベース接続エラー', 'login');
  }
}

// 振り分け処理
function db_access() {
  $dbh = func_get_arg(1);
  switch(func_get_arg(0)) {
    case 'login_admin':
      $adminname = func_get_arg(2);
      $password = func_get_arg(3);
      $rtn = login_admin($dbh, $adminname, $password);
    break;
    :
  }
  return $rtn;
}

// ユーザー認証（管理者機能）
function login_admin($dbh, $adminname, $password) {
  $sql = <<<EOF
    SELECT
      count(*)
    FROM
      tbl_task_admin_info
    WHERE
      adminname = :adminname
        AND
      password = :password
EOF;
  $stmt = $dbh->prepare($sql);
  $rtn = $stmt->execute(array(adminname=>$adminname, password=>$password));
  if($rtn === TRUE) {
    $rtn = $stmt->fetchColumn();
  }
  return $rtn;
}