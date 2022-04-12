<?php
//menu_index.js/sel_list_menuより
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
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- bootstrap5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- datepicker -->
  <link rel="stylesheet" href="/code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/css/index.css?ver=<?php echo time(); ?>">
  <link rel="stylesheet" href="/css/menu_contents.css?ver=<?php echo time(); ?>">
  <title>SELF PORTAL SITE for PRIVATE</title>
</head>

<body>
  <div class="container-fluid p-0">
    <header class="fixed-top">
      <div class="container-fluid bg-light h-100 d-flex justify-content-center align-items-center">
        <h1 class="m-0" title="topへ戻る">Task</h1>
      </div>
    </header>
    <main>
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb m-2">
          <li class="breadcrumb-item"><a href="/index.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Category</li>
        </ol>
      </nav>
      <div class="card">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-md-8 py-3 category">
              <div class="card">
                <div class="card-header text-center d-flex justify-content-between">
                  <div class="d-inline-block"><div id="category_name" class="d-inline-block"></div><span class="badge bg-success p-1 mx-1 category-name-change" data-bs-toggle="modal" data-bs-target="#name_change_modal">変更</span></div>
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
      </div>
      <a id="pagetop"><img src="/images/top-btn.svg" width="50" height="50" alt="topへ戻る" title="topへ戻る"></a>
    </main>
    <footer>
      <div class="bg-light h-100 footer d-flex justify-content-center align-items-center">
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
  <script src="/js/menu_contents.js?ver=<?php echo time(); ?>"></script>
</body>

</html>