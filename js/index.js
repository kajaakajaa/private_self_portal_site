$(()=> {
  setListShift();
});

//ページの先頭へ戻る
const pagetop = $('#pagetop, header');
const fadeOut = $('#pagetop');
$(window).scroll(()=> {
  if($(this).scrollTop() > 100) {
    pagetop.fadeIn();
  }
  else {
    fadeOut.fadeOut();
  }
})
pagetop.on('click', ()=> {
  $('body, html').animate({
    scrollTop: 0
  }, 400);
  return false;
})

//formデータ
function formDataTemplate() {
  let query = {};
  query['work_time'] = $('#work_time').val();
  query['home_time'] = $('#home_time').val();
  query['edit_task'] = $('textarea[name="edit_task"]').val();
  query['work_date'] = $('#datepicker').val();
  query['user_no'] = $('#user_no').val();
  return query;
}

//オンロード時に取得するデフォルト値
function setListShift() {
  $('#datepicker').datepicker().datepicker('setDate', 'today');
  let query = {};
      query['work_date'] = $('#datepicker').val();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=set_list_shift',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      $.each(data.user, (key, value)=> {
        $('#work_time').val(value.work_time);
        $('#home_time').val(value.home_time);
        $('#task_contents').html(value.task);
      });
    },
    function(jgHXR, textStatus, errorThrown) {
      console.log(jgHXR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

//出勤記録
function workTime() {
  const query = formDataTemplate();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=work_time',
    data: query,
    dataType: 'html'
  })
  .then(
    function(data) {
      console.log(data);
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

//退勤記録
function homeTime() {
  const query = formDataTemplate();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=home_time',
    data: query,
    dataType: 'html'
  })
  .then(
    function(data) {
      console.log(data);
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

//日付別でデータを取得
function getData() {
  const query = formDataTemplate();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=get_data',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      if(data == '') {
        $('#work_time').val('');
        $('#home_time').val('');
        $('#task_contents').html('');
      }
      else {
        $.each(data.user, (key, value)=> {
          $('#task_contents').html(value.task);
          $('#work_time').val(value.work_time);
          $('#home_time').val(value.home_time);
        });
      }
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

//前日・翌日ボタンでフォームに日付を反映
function getDay(num) {
  let query = formDataTemplate();
      query['num'] = num;
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=get_day',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      let date = data.work_date;
          date = date.replaceAll('-', '/');
      $('#datepicker').val(date);
      getData();
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

//本日予定の内容登録
function editTask() {
  const query = formDataTemplate();
  console.log(query);
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=edit_task',
    data: query,
    dataType: 'html'
  })
  .then(
    function(data) {
      console.log(data);
      setListShift();
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

//編集画面に登録済みタスク内容を反映させる
function reflectTask() {
  const query = formDataTemplate();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=reflect_task',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      $('textarea[name="edit_task"]').val(data.task);
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}