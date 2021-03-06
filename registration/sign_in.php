<?php
include_once('../session_logic/sessionLogic.php');
include_once('../config/console_log.php');

  // console_log('waayassa7 ：' . password_hash('waayassa7', PASSWORD_DEFAULT)); //rwqxh330@gmail.com
  // console_log('aaaa ：' . password_hash('aaaa', PASSWORD_DEFAULT)); //rwqxh8703@yahoo.co.jp
  // console_log('itsumi4649 ：' . password_hash('itsumi4649', PASSWORD_DEFAULT));

  $result = $logic->signIn();
  //クリックジャッキング対策
  header('X-FRAME-OPTIONS: DENY');
  // console_log($_SESSION);
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
  <link rel="stylesheet" href="/self_portal_site_private/css/index.css?<?php echo time(); ?>">
  <link rel="stylesheet" href="/self_portal_site_private/css/registration.css?ver=<?php echo time(); ?>">
  <title>SIGN IN</title>
</head>
<body>
  <div class="container-fluid p-0">
    <header class="fixed-top">
      <div class="container-fluid h-100 d-flex justify-content-center align-items-center">
        <h1 class="m-0">Task</h1>
      </div>
    </header>
    <main id="sign_in_wrapper">
      <?php if($result == true) : ?>
        <h5 class="text-center mx-3 mb-5 sign-in-out-refuse">&#x203B;既にログイン済みです。</h5>
        <div class="d-flex justify-content-center"><a href="/self_portal_site_private/">Myページへ</a></div>
      <?php else : ?>
        <h2 class="text-center mb-5 text-color">ログイン</h2>
        <form class="m-3">
          <input type="hidden" name="sign_in_token" id="sign_in_token" value="<?php echo $_SESSION['token']; ?>">
          <div class="form-group row d-flex justify-content-center m-3">
            <label for="user_name" class="col-md-2 col-form-label text-color">メール</label>
            <div class="col-md-7 col-lg-5">
              <input type="text" name="user_name" id="user_name" class="form-control">
              <p class="m-0" id="error_username"></p>
            </div>
          </div>
          <div class="form-group row d-flex justify-content-center m-3">
            <label for="password" class="col-md-2 col-form-label text-color">パスワード</label>
            <div class="col-md-7 col-lg-5">
              <input type="password" name="password" id="password" class="form-control">
              <p class="m-0" id="error_password"></p>
            </div>
          </div>
          <div class="col-12 mt-5 d-flex justify-content-center">
            <input type="checkbox" class="form-check-input" name="keep_sign_in" id="keep_sign_in">&nbsp;
            <label for="keep_sign_in" class="form-check-label text-color">ログインを維持する</label>
          </div>
          <div class="col-12 mt-3 d-flex justify-content-center">
            <button type="button" class="btn btn-primary" onClick="SignIn()">送信</button>
          </div>
          <div class="text-center m-3"><a href="/self_portal_site_private/registration/sign_up">新規登録</a></div>
        </form>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <!-- jsファイル -->
  <script src="/self_portal_site_private/js/regist.js?ver=<?php echo time(); ?>"></script>
</body>
</html>