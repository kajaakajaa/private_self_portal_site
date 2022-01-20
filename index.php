<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- bootstrap5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="/self_portal_site/css/index.css?ver=<?php echo time(); ?>">
  <title>SELF PORTAL</title>
</head>

<body>
  <div class="container-fluid p-0">
    <header>
      <div class="container-fluid bg-light h-100 d-flex justify-content-center align-items-center">
        <h1 class="m-0">TO DO</h1>
      </div>
    </header>
    <main>
      <div class="card">
        <div class="container-fluid">
          <div class="row">
            <div class="order-2 order-md-1 col-md-2 py-3">
              <div class="card">
                <div class="card-header">MENU</div>
                <div class="card-body"></div>
              </div>
            </div>
            <div class="order-1 order-md-2 col-md-7 py-3">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">日報</div>
                    <div class="card-body">
                      <form class="row">
                        <input type="hidden" name="user_no" id="user_no">
                        <div class="form-group col-12 text-center col-sm-6 text-sm-start">
                          <label for="work_time" class="col-md-3">出勤</label>
                          <div class="cal-md-9"><input type="time" name="work_time" id="work_time" class="form-control" onChange="workTime()"></div>
                        </div>
                        <div class="form-group col-12 text-center col-sm-6 text-sm-start">
                          <label for="home_time" class="col-md-3">退勤</label>
                          <div class="cal-md-9"><input type="time" name="home_time" id="home_time" class="form-control" onChange="homeTime()"></div>
                        </div>
                      </form>
                      <div class="cal-12 py-3">
                        <div class="card">
                          <div class="card-header">本日の予定</div>
                          <div class="card-body">あああ</div>
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
    </main>
    <footer>
      <div class="bg-light h-100 footer d-flex justify-content-center align-items-center">
        <p class="m-0"><small>&copy; 2022 Trigg</small></p>
      </div>
    </footer>
  </div>
  <script>
    const today = '<?php echo date ?>';
  //     <script>
  //   const today = '<?php echo date('Y-m-d'); ?>';
  //   let date = '<?php echo date("Y-m-d"); ?>';
  //   const position = $('.report_index').offset().top;
  //   const spd = 200;
  // </script>
  </script>

  <!-- jquery + bootstrap5 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <!-- jsファイル -->
  <script src="/self_portal_site/js/index.js?ver=<?php echo time(); ?>"></script>
</body>
</html>