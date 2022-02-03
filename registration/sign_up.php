<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- bootstrap5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="/self_portal_site/css/index.css?ver=<?php echo time(); ?>">
  <title>SIGN UP</title>
</head>
<body>
  <div class="container-fluid p-0">
    <header class="fixed-top">
      <div class="container-fluid bg-light h-100 d-flex justify-content-center align-items-center">
        <h1 class="m-0" title="topへ戻る">Task</h1>
      </div>
    </header>
    <main id="sign_up_wrapper">
      <h2 class="text-center mb-5">新規登録</h2>
      <form class="m-3">
        <div class="form-group row d-flex justify-content-center m-3">
          <label for="user_name" id="user_name" class="col-md-2 col-form-label">お名前<span class="badge bg-warning mx-1">必須</span></label>
          <div class="col-md-7">
            <input type="text" name="user_name" id="user_name" class="form-control">
            <p class="error-username m-0"></p>
          </div>
        </div>
        <div class="form-group row d-flex justify-content-center m-3">
          <label for="password" id="password" class="col-md-2 col-form-label">パスワード<span class="badge bg-warning mx-1">必須</span></label>
          <div class="col-md-7">
            <input type="password" name="password" id="password" class="form-control">
            <p class="error-password m-0"></p>
          </div>
        </div>
        <div class="col-12 mt-5 d-flex justify-content-center">
          <button class="btn btn-primary">登録する</button>
        </div>
      </form>
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
</body>
</html>