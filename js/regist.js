$(()=> {
});

function getFormData() {
  let query = {};
      query['user_name'] = $('#user_name').val();
      query['password'] = $('#password').val();
      query['password_confirm'] = $('#password_confirm').val();
  return query;
}

function clearForm() {
  $('#user_name').val('');
  $('#password').val('');
  $('#password_confirm').val('');
}

function errorCheck() {
  const query = getFormData();
  let errorCount = 0;
  if(query['user_name'] == '') {
    $('#error_username').html('&#x203B;名前は必須になります。');
    errorCount++;
  }
  else {
    $('#error_username').html('');
  }
  if(!query['password'].match(/[0-9a-zA-Z]{4,20}/)) {
    $('#error_password').html('&#x203B;パスワードは英数字で4〜20字範囲内で入力して下さい。');
    errorCount++;
  }
  else {
    $('#error_password').html('');
  }
  if(query['password_confirm'] != query['password']) {
    $('#error_password_confirm').html('&#x203B;パスワードが一致しません。');
    errorCount++;
  }
  else {
    $('#error_password_confirm').html('');
  }
  return errorCount;
}

//新規登録後にnoを取得
function getNo() {
  const query = getFormData();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/registration_sql_data.php?mode=get_no',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      window.location.href = '/self_portal_site/registration/registed.php';
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

function registUser() {
  const query = getFormData();
  const errorCount = errorCheck();
  if(errorCount == 0) {
    const query = getFormData();
    $.ajax({
      type: 'POST',
      url: '/self_portal_site/request/registration_sql_data.php?mode=duplicate_confirm',
      data: query,
      dataType: 'json'
    })
    .then(
      function(data) {
        console.log(data);
        if(data == false) {
          $.ajax({
            type: 'POST',
            url: '/self_portal_site/request/registration_sql_data.php?mode=regist_user',
            data: query,
            dataType: 'html'
          })
          .then(
            function(data) {
              console.log(data);
              $('#error_password').html('');
              getNo();
            },
            function(jgXHR, textStatus, errorThrown) {
              console.log(jgXHR);
              console.log(textStatus);
              console.log(errorThrown);
            }
          );
        }
        else {
          $('#error_password').html('&#x203B;既に登録されているパスワードになります。');
        }
      },
      function(jgXHR, textStatus, errorThrown) {
        console.log(jgXHR);
        console.log(textStatus);
        console.log(errorThrown);
      }
    );
  }
}

function Login() {
  const errorCount = errorCheck();
  if(errorCount == 0) {
    console.log('kajaa');
    const query = getFormData();
    $.ajax({
      type: 'POST',
      url: '/self_portal_site/request/registration_sql_data.php?mode=login',
      data: query,
      dataType: 'json'
    })
    .then(
      function(data) {
        console.log(data);
        if(data.user_name == query['user_name'] && data.password == query['password']) {
          console.log('ok');
        }
      },
      function(jgXHR, textStatus, errorThrown) {
        console.log(jgXHR);
        console.log(textStatus);
        console.log(errorThrown);
      }
    );
  }
}