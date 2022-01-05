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
  <!-- <div class="container">
    <div class="card mt-2">
      <div class="card-header text-center">Arakawa_Work_Suport</div>
      <div class="card-body row">
        <div class="card col-2">
          <div class="card-header text-nowrap">MENU</div>
        </div>
        <div class="card col-6 mr-2 ml-2">
          <div class="card-header">日報</div>
          <div class="card-body">
            <form>
              <input type="hidden" name="my_no" id="my_no">
              <div class="form-group row">
                <label for="work_time" class="col-sm-2 col-form-label">出勤</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="work_time" id="work_time" onChange="workTime()">
                </div>
              </div>
              <div class="form-group row">
                <label for="home_time" class="col-sm-2 col-form-label">退勤</label>
                <div class="col-sm-10">
                  <input class="form-control" type="text" name="home_time" id="home_time" onChange="homeTime()">
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="card col-3">
          <div class="card-header">NOTE</div>
        </div>
      </div>
    </div>
  </div> -->

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2">
        <div class="card">
          <div class="card-header">MENU</div>
          <div class="card-body"></div>
        </div>
      </div>
      <div class="col-md-7">
        <div class="card">
          <div class="card-header">日報</div>
          <div class="card-body">
            <div class="row">
              <label for="work_time" class="col-md-6">出勤</label>
              <div class="cal-md-6"><input type="text" name="work_time" id="work_time"></div>
            </div>
            <div class="row">
              <label for="home_time" class="col-md-6">退勤</label>
              <div class="cal-md-6"><input type="text" name="home_time" id="home_time"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-header">NOTE</div>
          <div class="card-body"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- jquery + bootstrap5 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <!-- jsファイル -->
  <script src="/self_portal_site/js/index.js?ver=<?php echo time(); ?>"></script>
</body>

</html>