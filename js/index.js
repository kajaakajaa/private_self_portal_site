$(()=> {
  setListShift();

  //datepickerの表示
  $('#datepicker').datepicker();
});

//ページの先頭へ戻る
$(window).scroll(()=> {
  if($(this).scrollTop() > 100) {
    $('#pagetop').fadeIn();
  }
  else {
    $('#pagetop').fadeOut();
  }
})

//formデータ
function formDataTemplate() {
  let date = $('#datepicker').val();
      date = date.slice(0, 10);
  let query = {};
      query['work_time'] = $('#work_time').val();
      query['home_time'] = $('#home_time').val();
      query['edit_task'] = $('textarea[name="edit_task"]').val();
      query['work_date'] = date;
      query['user_no'] = $('#user_no').val();
  return query;
}

//オンロード時に取得するデフォルト値
function setListShift() {
  let date = $('#datepicker').val();
      date = date.slice(0, 10);
  let query = {};
      query['work_date'] = date;
      if($('#user_no').val() != '') {
        query['user_no'] = $('#user_no').val();
      }
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=set_list_shift',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      $('#user_no').val(data.user_no.no);
      $('#work_time').val(data.user.work_time);
      $('#home_time').val(data.user.home_time);
      $('#task_contents').html(data.task.schedule);
      replaceLink();
    },
    function(jgHXR, textStatus, errorThrown) {
      console.log(jgHXR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

//テキスト内リンクへの変換
function replaceLink() {
  $('.task-contents').each(function() {
    $(this).html($(this).html().replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,
    '<a href="$1" target="_blank" rel="noopener noreferrer" style="word-break: break-all;">$1</a>'));
  });
}

//出勤記録
function workTime() {
  const query = formDataTemplate();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=work_time',
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
    url: '/self_portal_site_private/request/sql_data.php?mode=home_time',
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

//getDay()でデータを取得。＋datepickerで日付変更。
function getData() {
  let query = formDataTemplate();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=get_data',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      let date = data.user_data.work_date;
          date = date.replaceAll('-', '/');
      $('#task_contents').html(data.task.schedule);
      replaceLink();
      $('#work_time').val(data.user_data.work_time);
      $('#home_time').val(data.user_data.home_time);
      $('#datepicker').val(date);
    },
    function(jgXHR,textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
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
      query['user_no'] = $('#user_no').val();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=get_day',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      let query2 = {};
          query2['user_no'] = $('#user_no').val();
          query2['work_date'] = data.work_date.work_date;
          query2['work_date'] = query2['work_date'].slice(0, 10);
      $.ajax({
        type: 'POST',
        url: '/self_portal_site_private/request/sql_data.php?mode=get_data',
        data: query2,
        dataType: 'json'
      })
      .then(
        function(data) {
          console.log(data);
          let date = data.user_data.work_date;
              date = date.replaceAll('-', '/');
          $('#task_contents').html(data.task.schedule);
          replaceLink();
          $('#work_time').val(data.user_data.work_time);
          $('#home_time').val(data.user_data.home_time);
          $('#datepicker').val(date);
        },
        function(jgXHR,textStatus, errorThrown) {
          console.log(jgXHR);
          console.log(textStatus);
          console.log(errorThrown);
        }
      );
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
  console.log(query['edit_task']);
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=edit_task',
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
    url: '/self_portal_site_private/request/sql_data.php?mode=reflect_task',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      $('textarea[name="edit_task"]').val(data.schedule);
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}