<?php
//menu_index.js/set_list_menuより
include_once('config/db_connect.php');
include_once('config/console_log.php');
  $menuNo = $_GET['menu_no'];
  $userNo = $_GET['user_no'];
  $sql = <<<EOF
    SELECT
      category_name
    FROM
      tbl_task_menu
    WHERE
      no = :menu_no
        AND
      user_no = :user_no
        AND
      del_flg = 0
        AND
      status = 0
EOF;
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':menu_no', $menuNo, PDO::PARAM_INT);
  $stmt->bindParam(':user_no', $userNo, PDO::PARAM_INT);
  $stmt->execute();
  $array = $stmt->fetch(PDO::FETCH_ASSOC);
  $categoryName = $array['category_name'];
  session_start();
  session_regenerate_id();
  //ログイン維持、クッキーログイン
  if(isset($_COOKIE['keep_session'])) {
    $sql = <<<EOF
      SELECT
        no
      FROM
        tbl_task_user
      WHERE
        cookie_pass = :cookie_pass
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':cookie_pass', $_COOKIE['keep_session'], PDO::PARAM_STR);
    $stmt->execute();
    $user = array();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //データベースのcookie_passと値が違う場合の代替え認証(pc/sp間両方ログインし片方でログアウトした場合に生じる)
    if($user == null) {
      $sql = <<<EOF
        SELECT
          no
        FROM
          tbl_task_user
        WHERE
          user_name = :user_name
            AND
          password = :password
EOF;
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':user_name', $_SESSION['user_name'], PDO::PARAM_STR);
      $stmt->bindParam(':password', $_SESSION['password'], PDO::PARAM_STR);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
  }
  //ログイン非維持
  elseif($_SESSION != null && $_COOKIE['keep_session'] == null) {
    $sql = <<<EOF
      SELECT
        no
      FROM
        tbl_task_user
      WHERE
        user_name = :user_name
          AND
        password = :password
EOF;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_name', $_SESSION['user_name'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $_SESSION['password'], PDO::PARAM_STR);
    $stmt->execute();
    $user = array();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
  }
  console_log($_SESSION);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <!-- bootstrap5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- datepicker -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
  <!-- favicon -->
  <link rel="icon" href="/self_portal_site_private/images/favicon.ico">
    <!-- windows version別 -->
  <link rel=”icon” type=”image/vnd.microsoft.icon” href=“/self_portal_site_private/images/favicon.ico>
  <link rel=”shortcut icon” href=”/self_portal_site_private/images/favicon.ico>
    <!-- iphone -->
  <link rel="apple-touch-icon" sizes="180x180" href="/self_portal_site_private/images/apple-touch-icon.png">
    <!-- android -->
  <link rel="icon" type="image/png" href="/self_portal_site_private/images/android-touch-icon.png" sizes="192x192">
  <!-- css -->
  <link rel="stylesheet" href="/self_portal_site_private/css/index.css?ver=<?php echo time(); ?>">
  <link rel="stylesheet" href="/self_portal_site_private/css/menu_contents.css?ver=<?php echo time(); ?>">
  <title>SELF PORTAL SITE for PRIVATE</title>
</head>
<body>
  <div class="container-fluid p-0">
    <header class="fixed-top">
      <div class="container-fluid h-100 d-flex justify-content-center align-items-center">
        <h1 class="m-0">Task</h1>
      </div>
    </header>
    <main id="menu_contents_main">
      <?php if(isset($user['no']) && $user['no'] == $userNo) : ?>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" id="header_navbar">
          <ol class="breadcrumb p-2 m-0">
            <li class="breadcrumb-item"><a href="/self_portal_site_private/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Category</li>
          </ol>
        </nav>
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-md-8 py-3 category">
              <div class="card">
                <div class="card-header text-center d-flex justify-content-between" id="category_header_name">
                  <div class="d-inline-block">
                    <span class="badge p-1 mx-1 category-name-change" id="change_name" data-bs-toggle="modal" data-bs-target="#name_change_modal">変更</span>
                    <div id="category_name" class="d-inline-block"></div>
                  </div>
                  <div class="btn-wrapper d-inline-block">
                    <input type="hidden" name="user_no" id="user_no" value="<?php echo $userNo; ?>">
                    <input type="hidden" name="menu_no" id="menu_no" value="<?php echo $menuNo; ?>">
                    <input type="hidden" id="duplicate_check">
                    <input type="hidden" id="empty_check">
                    <button type="button" id="edit_category_btn" class="btn btn-primary p-1 py-0">編集</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#confirm_delete" id="delete_confirm"
                      class="btn btn-secondary p-1 py-0">削除</button>
                    <button type="button" class="btn-close" id="edit_category_close" aria-label="Close"></button>
                  </div>
                </div>
                <!-- 名前変更Modal -->
                <div class="modal fade" id="name_change_modal" tabindex="-1" aria-labelledby="name_change_modal_label" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="name_change_modal_label">MENU（カテゴリー名）変更</h5>
                        <button type="button" class="btn-close name-change-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body p-1">
                        <div>
                          <input type="text" name="name_change" id="name_change" class="form-control">
                          <p class="text-center error-messages"></p>
                        </div>
                      </div>
                      <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-primary" id="change_name_btn">登録</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- 編集Modal -->
                <div class="modal fade" id="confirm_delete" tabindex="-1" aria-labelledby="confirm_delete_label"
                  aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="confirm_delete_label"><?php echo $categoryName; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p>このカテゴリーを削除しても宜しいですか？</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" id="delete_category_contents" class="btn btn-primary"
                          data-bs-dismiss="modal" aria-label="Close">Yes</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                          aria-label="Close">No</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body" id="edit_category">
                  <p id="category_contents"></p>
                  <div id="category_contents_wrapper">
                    <textarea class="form-control" rows="10" name="edit_category_contents"
                      id="edit_category_contents"></textarea>
                    <div class="d-flex justify-content-center">
                      <button type="button" id="close_category" class="btn btn-primary m-3 mb-0">登録</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <a href="#" id="pagetop"><img src="/self_portal_site_private/images/top-btn.svg" width="50" height="50" alt="topへ戻る" title="topへ戻る"></a>
      <?php elseif(isset($user['no']) && $user['no'] != $userNo) : ?>
        <h5 class="text-center mx-3 mb-5 request-refuse">&#x203B;リクエストされたページへは遷移出来ません。</h5>
        <div class="d-flex justify-content-center"><a href="/self_portal_site_private/">Myページへ</a></div>
      <?php else :
        header('Location: https://kajaaserver.com/self_portal_site_private/registration/sign_in');
      ?>
      <?php endif; ?>
    </main>
    <footer>
      <div class="h-100 footer d-flex justify-content-center align-items-center">
        <p class="m-0"><small>&copy; 2022 Arakawa</small></p>
      </div>
    </footer>
  </div>

  <!-- jquery + bootstrap5 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <!-- datepicker -->
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
  <script src="https://rawgit.com/jquery/jquery-ui/master/ui/i18n/datepicker-ja.js"></script>
  <!-- jsファイル -->
  <script src="/self_portal_site_private/js/menu_contents.js?ver=<?php echo time(); ?>"></script>
</body>

</html>