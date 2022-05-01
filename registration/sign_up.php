<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- bootstrap5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="/self_portal_site_private/css/index.css?ver=<?php echo time(); ?>">
  <link rel="stylesheet" href="/self_portal_site_private/css/registration.css?ver=<?php echo time(); ?>">
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
  <title>SIGN UP</title>
</head>
<body>
  <div class="container-fluid p-0">
    <header class="fixed-top">
      <div class="container-fluid h-100 d-flex justify-content-center align-items-center">
        <h1 class="m-0">Task</h1>
      </div>
    </header>
    <main id="sign_up_wrapper">
      <div id="sign_up">
        <h2 class="text-center mb-5 text-color">新規登録</h2>
        <form class="m-3">
          <div class="form-group row d-flex justify-content-center m-3">
            <label for="user_name" class="col-md-2 col-form-label text-color">メール<span class="badge bg-warning mx-1 warning-text-color">必須</span></label>
            <div class="col-md-7 col-lg-5">
              <input type="text" name="user_name" id="user_name" class="form-control">
              <p class="m-0" id="error_username"></p>
            </div>
          </div>
          <div class="form-group row d-flex justify-content-center m-3">
            <label for="password" class="col-md-2 col-form-label text-color">パスワード<span class="badge bg-warning mx-1 warning-text-color">必須</span></label>
            <div class="col-md-7 col-lg-5">
              <input type="password" name="password" id="password" class="form-control">
              <p class="m-0" id="error_password"></p>
            </div>
          </div>
          <div class="form-group row d-flex justify-content-center m-3">
            <label for="password_confirm" class="col-md-2 col-form-label text-color">パスワード確認<span class="badge bg-warning mx-1 warning-text-color">必須</span></label>
            <div class="col-md-7 col-lg-5">
              <input type="password" name="password_confirm" id="password_confirm" class="form-control">
              <p class="m-0" id="error_password_confirm"></p>
            </div>
          </div>
          <div class="col-12 mt-5 d-flex justify-content-center">
            <button type="button" class="btn btn-primary" onClick="registUser()">登録する</button>
          </div>
        </form>
      </div>
      <!-- 登録完了画面 -->
      <div class="container sign-up-wrapper" id="complete">
        <h4 class="m-4 text-center text-color">新規登録完了。</h4>
        <p class="text-center">登録確認のメールを送信致しましたので、メール内添え付けのリンクよりログインして下さい。</p>
      </div>
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