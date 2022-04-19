<?php
include_once('../session_logic/sessionLogic.php');
include_once('../config/console_log.php');

  $result = $logic->signOut();
  $timeout = '&#x203B;セッションがタイムアウト、又はログアウト済みです。再ログインして下さい。';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <!-- bootstrap5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="/self_portal_site_private/css/index.css?ver=<?php echo time(); ?>">
  <link rel="stylesheet" href="/self_portal_site_private/css/registration.css?ver=<?php echo time(); ?>">
  <title>SIGN OUT</title>
</head>
<body>
  <div class="container-fluid p-0">
    <header class="fixed-top">
      <div class="container-fluid bg-light h-100 d-flex justify-content-center align-items-center">
        <h1 class="m-0" title="topへ戻る">Task</h1>
      </div>
    </header>
    <main id="sign_out_wrapper">
      <?php if($result == true) : ?>
        <h5 class="text-center mx-3 mb-5 sign-in-out-refuse">ログアウトしました。</h5>
        <div class="d-flex justify-content-center"><a href="/self_portal_site_private/registration/sign_in.php">ログインページへ</a></div>
      <?php else : ?>
        <h5 class="text-center mx-3 mb-5 sign-in-out-refuse"><?php echo $timeout; ?></h5>
        <div class="d-flex justify-content-center"><a href="/self_portal_site_private/registration/sign_in.php">ログインページへ</a></div>
      <?php endif; ?>
    </main>
    <footer>
      <div class="bg-light h-100 footer d-flex justify-content-center align-items-center">
        <p class="m-0"><small>&copy; 2022 Arakawa</small></p>
      </div>
    </footer>
  </div>

  <!-- jquery + bootstrap5 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <!-- jsファイル -->
  <script src="/self_portal_site_private/js/regist.js?ver=<?php echo time(); ?>"></script>
</body>
</html>