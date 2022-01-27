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
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/self_portal_site/css/index.css?ver=<?php echo time(); ?>">
  <title>SELF PORTAL</title>
</head>

<body>
  <div class="container-fluid p-0">
    <header>
      <div class="container-fluid bg-light h-100 d-flex justify-content-center align-items-center">
        <h1 class="m-0">TASK</h1>
      </div>
    </header>
    <main>
      <div class="card">
        <div class="container-fluid">
          <div class="row">
            <div class="order-2 order-md-1 col-md-2 py-3 menu">
              <div class="card">
                <div class="card-header">MENU<span class="badge bg-primary mx-2 add-menu-btn" data-bs-toggle="modal" data-bs-target="#menu_modal">追加</span></div>
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
                          <textarea name="add_menu" id="add_menu" rows="3" class="form-control"></textarea>
                          <p id="duplicated_message" class="text-center"></p>
                        </div>
                      </div>
                      <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-primary edit-btn" id="add_menu_btn">登録</button>
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
                    <div class="card-header">日報
                      <input type="text" id="datepicker" class="mx-3" name="datepicker" onChange="getData()">
                      <button type="button" class="btn btn-secondary p-1 px-sm-2 other-day" onClick="getDay(1)">前日</button>
                      <button type="button" class="btn btn-secondary p-1 px-sm-2 other-day" onClick="getDay(2)">翌日</button>
                    </div>
                    <div class="card-body report-contents">
                      <form class="row">
                        <input type="hidden" name="user_no" id="user_no">
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
      <a id="pagetop"><img src="/self_portal_site/images/top-btn.svg" width="50" height="50" alt="topへ戻る"></a>
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
  <script src="/self_portal_site/js/index.js?ver=<?php echo time(); ?>"></script>
  <script src="/self_portal_site/js/menu_index.js?ver=<?php echo time(); ?>"></script>
</body>

</html>