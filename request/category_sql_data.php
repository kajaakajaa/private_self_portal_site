<?php
include_once('../../config/db_connect.php');
$mode = $_GET['mode'];

function sanitized($str) {
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}
switch($mode) {
  case 'set_list_category':
    $userNo = $_POST['user_no'];
    $menuNo = $_POST['menu_no'];
    $sql = <<<EOF
      SELECT
        contents,
        category_name
      FROM
        tbl_task_menu mnu
          LEFT JOIN
            tbl_task_menu_category ctg
          ON
            mnu.no = ctg.menu_no
      WHERE
        no = :menu_no
          AND
        mnu.del_flg = 0
          AND
        mnu.status = 0
          AND
        ctg.del_flg = 0
          AND
        ctg.status = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':menu_no', $menuNo, PDO::PARAM_INT);
    $stmt->execute();
    $array = array();
    $array = $stmt->fetch(PDO::FETCH_ASSOC);
    $array['contents_nobr'] = $array['contents'];
    $array['contents'] = sanitized($array['contents'], false);
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;

  case 'regist_category_contents':
    $userNo = $_POST['user_no'];
    $menuNo = $_POST['menu_no'];
    $contents = $_POST['contents'];
    $sql = <<<EOF
      INSERT
        tbl_task_menu_category
        (
          contents,
          user_no,
          menu_no,
          regist_time
        ) VALUES (
          :contents,
          :user_no,
          :menu_no,
          NOW()
        ) ON DUPLICATE KEY UPDATE
          contents = :contents,
          user_no = :user_no,
          menu_no = :menu_no,
          update_time = NOW()
  EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_STR);
    $stmt->bindParam(':menu_no', $menuNo, PDO::PARAM_STR);
    $stmt->bindParam(':contents', $contents, PDO::PARAM_STR);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'delete_category_contents':
    $menuNo = $_POST['menu_no'];
    $sql = <<<EOF
    UPDATE
      tbl_task_menu_category
    SET
      del_flg = 1,
        AND
      update_time = NOW()
    WHERE
      menu_no = :menu_no
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':menu_no', $menuNo, PDO::PARAM_INT);
    $stmt->execute();
    $sql = <<<EOF
      UPDATE
        tbl_task_menu
      SET
        del_flg = 1,
        update_time = NOW()
      WHERE
        no = :menu_no
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':menu_no', $menuNo, PDO::PARAM_INT);
    $stmt->execute();
    var_dump($stmt->errorInfo());
  break;

  case 'check_error':
    $userNo = $_POST['user_no'];
    $sql = <<<EOF
      SELECT
        category_name
      FROM
        tbl_task_menu
      WHERE
        user_no = :user_no
          AND
        del_flg = 0
          AND
        status = 0
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
    $stmt->execute();
    $array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $array['category'][] = $row;
    }
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($array);
  break;
}