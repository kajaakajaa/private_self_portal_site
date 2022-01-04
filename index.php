<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- bootstrap4 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="/self_portal_site/css/index.css?ver=<?php echo time(); ?>">
  <title>SELF PORTAL</title>
</head>

<body>
  <div class="container">
    <div class="card mt-2">
      <div class="card-header text-center">Arakawa_Work_Suport</div>
      <div class="card-body d-flex justify-content-between">
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
                  <input class="form-control" type="text" name="home_time" id="home_time" onChange="home_time()">
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
  </div>

  <!-- jquery + bootstrap4 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <!-- jsファイル -->
  <script src="/self_portal_site/js/index.js?ver=<?php echo time(); ?>"></script>
</body>

</html>