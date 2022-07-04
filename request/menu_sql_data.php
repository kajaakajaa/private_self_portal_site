<?php
include_once('../config/db_connect.php');
include_once('../config/console_log.php');

$mode = $_GET['mode'];
switch($mode) {
  case 'set_list_menu':
    session_start();
    //通常ログイン時
    if($_SESSION != null && $_POST['user_no'] == null) {
      $userName = $_SESSION['user_name'];
      $passWord = $_SESSION['password'];
      $sql = <<<EOF
        SELECT
          mnu.no AS menu_no,
          category_name
        FROM
          tbl_task_menu AS mnu
            LEFT JOIN
              tbl_task_user AS usr
            ON
              usr.no = mnu.user_no
        WHERE
          usr.user_name = :user_name
            AND
          usr.password = :password
            AND
          mnu.del_flg = 0
        ORDER BY
          mnu.no DESC
EOF;
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
      $stmt->bindParam(':password', $passWord, PDO::PARAM_STR);
      $stmt->execute();
      $array = array();
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $array['user'][] = $row;
      }
      $sql = <<<EOF
        SELECT
          no
        FROM
          tbl_task_user
        WHERE
          user_name = :user_name
            AND
          password = :password
            AND
          del_flg = 0
            AND
          status = 0
  EOF;
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':user_name', $userName, PDO::PARAM_STR);
      $stmt->bindParam(':password', $passWord, PDO::PARAM_STR);
      $stmt->execute();
      $array['user_no'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //クッキーログイン時
    else if(isset($_POST['user_no']) && $_SESSION == null) {
      $userNo = $_POST['user_no'];
      $sql = <<<EOF
      SELECT
        mnu.no AS menu_no,
        category_name
      FROM
        tbl_task_menu AS mnu
          LEFT JOIN
            tbl_task_user AS usr
          ON
            usr.no = mnu.user_no
      WHERE
        usr.no = :user_no
          AND
        mnu.del_flg = 0
      ORDER BY
        mnu.no DESC
EOF;
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
      $stmt->execute();
      $array = array();
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $array['user'][] = $row;
      }
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
    exit;
  break;

  case 'add_menu':
    $categoryName = $_POST['category_name'];
    $userNo = $_POST['user_no'];
    $sql = <<<EOF
      INSERT
        tbl_task_menu
        (
          category_name,
          user_no,
          regist_time
        ) VALUES (
          :category_name,
          :user_no,
          NOW()
        )
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':category_name', $categoryName, PDO::PARAM_STR);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'change_menu_name':
    $userNo = $_POST['user_no'];
    $menuNo = $_POST['menu_no'];
    $categoryName = $_POST['change_category_name'];
    $sql = <<<EOF
      UPDATE
        tbl_task_menu
      SET
        category_name = :category_name
      WHERE
        user_no = :user_no
          AND
        no = :menu_no
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':category_name', $categoryName, PDO::PARAM_STR);
    $stmt->bindParam('user_no', $userNo, PDO::PARAM_INT);
    $stmt->bindParam('menu_no', $menuNo, PDO::PARAM_INT);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;
}