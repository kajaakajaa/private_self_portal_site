<?php
include_once('config/db_connect.php');
include_once('config/console_log.php');

  //日付取得
  $year = date('Y');
  $month = date('m');
  $day = date('d');
  $dw = date('w');
  $week = array('日','月','火','水','木','金','土');
  $timestamp = mktime(0,0,0,$month,$day,$year);
  $date = date('Y/m/d');
  $date .= '(' . $week[$dw] . ')';

  // ini_set('session.gc_maxlifetime', 86400);
  // ini_set('session.cookie_lifetime ', 86400);
  // session_set_cookie_params(0, 'https://kajaaserver.com/self_portal_site_private/index.php');
  session_start();
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
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/self_portal_site_private/css/index.css?ver=<?php echo time(); ?>">
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
      <?php if($_SESSION['user_name'] && $_SESSION['password']) : ?>
      <nav aria-label="breadcrumb" class="d-flex justify-content-between">
        <ol class="breadcrumb m-2">
          <li class="breadcrumb-item active" aria-current="page">Home</li>
        </ol>
        <div class="me-4 d-flex align-items-center">
          <a href="/self_portal_site_private/registration/sign_out.php">ログアウト&gt;</a>
        </div>
      </nav>
      <div class="card">
        <div class="container-fluid">
          <div class="row">
            <div class="order-2 order-md-1 col-md-2 py-3 menu">
              <div class="card">
                <div class="card-header">MENU<span class="badge bg-primary mx-2 add-menu-btn" data-bs-toggle="modal" data-bs-target="#menu_modal" title="カテゴリーの追加">追加&plus;</span></div>
                <div class="card-body">
                  <ul id="menu_list"></ul>
                </div>
                <!-- Menu-Modal -->
                <div class="modal fade" id="menu_modal" tabindex="-1" area-labelledby="menu_modal_label" area-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="menu_modal_label">MENU（カテゴリー名）追加</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body p-1">
                        <div>
                          <input type="text" name="add_menu" id="add_menu" class="form-control"></input>
                          <p class="text-center error-messages"></p>
                        </div>
                      </div>
                      <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-primary" id="add_menu_btn">登録</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="order-1 order-md-2 col-md-7 py-3 report">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header date-picker-wrapper">日報
                      <input type="text" id="datepicker" class="mx-3" name="datepicker" value="<?php echo $date; ?>" onClick="onDatepicker()" onChange="getData()">
                      <div class="get-day-wrapper d-inline-block">
                        <button type="button" class="btn btn-secondary p-1 px-sm-2 other-day" onClick="getDay(1)">前日</button>
                        <button type="button" class="btn btn-secondary p-1 px-sm-2 other-day" onClick="getDay(2)">翌日</button>
                      </div>
                    </div>
                    <div class="card-body report-contents">
                      <form class="row">
                        <input type="hidden" name="user_no" id="user_no" value="<?php echo $userNo; ?>">
                        <div class="form-group col-12 text-center col-sm-6 text-sm-start">
                          <label for="work_time" class="col-md-3">出勤</label>
                          <div class="cal-md-9"><input type="time" name="work_time" id="work_time" class="form-control"
                              onChange="workTime()"></div>
                        </div>
                        <div class="form-group col-12 text-center col-sm-6 text-sm-start">
                          <label for="home_time" class="col-md-3">退勤</label>
                          <div class="cal-md-9"><input type="time" name="home_time" id="home_time" class="form-control"
                              onChange="homeTime()"></div>
                        </div>
                      </form>
                      <div class="cal-12 py-3">
                        <div class="card">
                          <div class="card-header">本日の予定</div>
                          <div class="card-body" data-bs-toggle="modal" data-bs-target="#report_modal" onClick="reflectTask()">
                            <!-- Button trigger modal -->
                            <p id="task_contents"></p>
                          </div>
                          <!-- Modal -->
                          <div class="modal fade" id="report_modal" tabindex="-1" aria-labelledby="report_modal_label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="report_modal_label">本日の予定</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-1">
                                  <div>
                                    <textarea class="form-control" name="edit_task" id="edit_task" rows="8"></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                  <button type="button" class="btn btn-primary edit-btn" data-bs-dismiss="modal" onClick="editTask()">登録</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="order-3 order-md-3 col-md-3 py-3">
              <div class="card">
                <div class="card-header">NOTE</div>
                <div class="card-body"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <a id="pagetop"><img src="/self_portal_site_private/images/top-btn.svg" width="50" height="50" alt="topへ戻る" title="topへ戻る"></a>
      <?php else : 
        header('Location: https://kajaaserver.com/self_portal_site_private/registration/sign_in.php');
      ?>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <!-- datepicker -->
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
  <script src="https://rawgit.com/jquery/jquery-ui/master/ui/i18n/datepicker-ja.js"></script>
  <!-- jsファイル -->
  <script src="/self_portal_site_private/js/index.js?ver=<?php echo time(); ?>"></script>
  <script src="/self_portal_site_private/js/menu_index.js?ver=<?php echo time(); ?>"></script>
</body>
</html>