//sign_in/upフォーム
function getFormData() {
  let query = {};
    query['user_name'] = $('#user_name').val();
    query['password'] = $('#password').val();
    query['password_confirm'] = $('#password_confirm').val();
    query['token'] = $('#sign_in_token').val();
  return query;
}

function clearForm() {
  $('#user_name').val('');
  $('#password').val('');
  $('#password_confirm').val('');
}

function signUpCheck() {
  const query = getFormData();
  let errorCount = 0;
  let pattern = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]+\.[A-Za-z0-9]+$/;
  if(query['user_name'] == '') {
    $('#error_username').html('&#x203B;名前は必須になります。');
    errorCount++;
  }
  else if(!query['user_name'].match(pattern)) {
    $('#error_username').html('&#x203B;正しいメールアドレスフォームを入力して下さい。');
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

function signInCheck() {
  const query = getFormData();
  let errorCount = 0;
  let pattern = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]+\.[A-Za-z0-9]+$/;
  if(query['user_name'] == '') {
    $('#error_username').html('&#x203B;名前は必須になります。');
    errorCount++;
  }
  else if(!query['user_name'].match(pattern)) {
    $('#error_username').html('&#x203B;正しいメールアドレスフォームを入力して下さい。');
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
  return errorCount;
}

function registUser() {
  const query = getFormData();
  const errorCount = signUpCheck();
  if(errorCount == 0) {
    const query = getFormData();
    $.ajax({
      type: 'POST',
      url: '/self_portal_site_private/request/registration_sql_data.php?mode=regist_user',
      data: query,
      dataType: 'json'
    })
    .then(
      function(data) {
        console.log(data);
        if(data.result == true) {
          $('#error_password').html('');
          $('#sign_up').hide();
          $('#complete').show();
          let query2 = {};
              query2['user_name'] = data.user_name.user_name;
          $.ajax({
            type: 'POST',
            url: '/self_portal_site_private/request/send_mail?mode=regist_info',
            data: query2,
            dataType: 'html'
          })
          .then(
            (data)=> {
              console.log(data);
            },
            (jgXHR, textStatus, errorThrown)=> {
              console.log(jgXHR);
              console.log(textStatus);
              console.log(errorThrown);
            }
          );
        }
        else {
          $('#error_username').html('&#x203B;既に使用されているメールアドレスになります。');
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

function SignIn() {
  const errorCount = signInCheck();
  let query = getFormData();
      query['status'] = $('input[name="keep_sign_in"]').prop('checked');
  if(errorCount == 0) {
    $.ajax({
      type: 'POST',
      url: '/self_portal_site_private/request/registration_sql_data.php?mode=sign_in',
      data: query,
      dataType: 'json'
    })
    .then(
      (data)=> {
        if(data) {
          console.log('kajaa');
          let user = {};
              user['user_name'] = data.user_name;
          $('#error_password').html('');
          window.location.href = '/self_portal_site_private/';
          $.ajax({
            type: 'POST',
            url: '/self_portal_site_private/request/send_mail?mode=sign_info',
            data: user,
            dataType: 'html'
          })
          .then(
            (data)=> {
            },
            (jgXHR, textStatus, errorThrown)=> {
              console.log(jgXHR);
              console.log(textStatus);
              console.log(errorThrown);
            }
          );
        }
        else if(data == false) {
          //tokenが不一致な場合
          $('#error_password').html('&#x203B;登録がお済みでない様です。');
        }
      },
      (jgXHR, textStatus, errorThrown)=> {
        console.log(jgXHR);
        console.log(textStatus);
        console.log(errorThrown);
      }
    );
  }
}

function signOut() {
  let query = {};
      query['user_no'] = $('#user_no').val();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/registration_sql_data.php?mode=sign_out',
    data: query,
    dataType: 'html'
  })
  .then(
    (data)=> {
      console.log(data);
      window.location.href = '/self_portal_site_private/registration/sign_out';
    },
    (jgXHR, textStatus, errorThrown)=> {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}