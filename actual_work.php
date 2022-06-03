<?php
include_once('config/db_connect.php');
include_once('config/console_log.php');
include_once('session_logic/sessionLogic.php');

  //日付取得
  $year = date('Y');
  $month = date('m');
  $day = date('d');
  $dw = date('w');
  $week = array('日','月','火','水','木','金','土');
  $timestamp = mktime(0,0,0,$month,$day,$year);
  $date = date('Y/m/d');
  $date .= '(' . $week[$dw] . ')';

  session_start();
  session_regenerate_id();
  console_log($_SESSION);
  $result = false;
  //クッキーログイン時（cookie情報が在り、且つcookie認証が通ればtrue）
  if(isset($_COOKIE['keep_session']) && $_SESSION['user_name'] == null && $_SESSION['password'] == null) {
    $sql = <<<EOF
      SELECT
        no,
        cookie_pass
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
    $userNo = $user['no'];
    $result = $user != null ? true : false;
  }
  //ログインsession情報が在ればtrue（ログイン維持/非維持関係なくuser_name/passwordセッションが優先)
  elseif(isset($_SESSION['user_name']) && isset($_SESSION['password'])) {
    $result = true;
  }
  if($result == false) {
    header('Location: /self_portal_site_private/registration/sign_out');
  }
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
  <link rel="stylesheet" href="/self_portal_site_private/css/registration.css?ver=<?php echo time(); ?>">
  <link rel="stylesheet" href="/self_portal_site_private/css/actual_work.css?ver=<?php echo time(); ?>">
  <title>SELF PORTAL SITE for PRIVATE</title>
</head>
<body>
  <div class="container-fluid p-0">
    <header class="fixed-top">
      <div class="container-fluid h-100 d-flex justify-content-center align-items-center">
      <h1 class="m-0">Task</h1>
      </div>
    </header>
    <main id="main_index">
      <?php if($result == true) : ?>
        <input type="hidden" name="user_no" id="user_no" value="<?php echo $userNo; ?>">
        <nav aria-label="breadcrumb" class="d-flex justify-content-between" id="header_navbar">
          <ol class="breadcrumb m-2">
            <li class="breadcrumb-item active" aria-current="page"><a href="/self_portal_site_private/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Actual Work</li>
          </ol>
          <div class="me-4 d-flex align-items-center">
            <a id="sign_out" onClick="signOut()">ログアウト&gt;</a>
          </div>
        </nav>
        <div id="acutual_work_wrpper">
          <div id="actual_work_header"><?php echo date('Y') . '&nbsp;年'; ?></div>
          <div id="actual_work_body"><ul></ul></div>
        </div>
        <a href="#" id="pagetop"><img src="/self_portal_site_private/images/top-btn.svg" width="50" height="50" alt="topへ戻る" title="topへ戻る"></a>
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
  <script src="/self_portal_site_private/js/regist.js?ver=<?php echo time(); ?>"></script>
  <script src="/self_portal_site_private/js/actual_work.js?ver=<?php echo time(); ?>"></script>
</body>
</html>