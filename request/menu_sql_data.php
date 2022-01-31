<?php
include_once('../../config/db_connect.php');

$mode = $_GET['mode'];
switch($mode) {
  case 'set_list_menu':
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
        mnu.del_flg = 0
          AND
        usr.del_flg = 0
          AND
        usr.no = user_no
      ORDER BY
        mnu.no DESC
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $array['menu'][] = $row;
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
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