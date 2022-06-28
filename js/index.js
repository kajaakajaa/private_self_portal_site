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

function salary([[work_time, home_time], date]) {
  let work_time_ts = date + ' ' + work_time;
      work_time_ts = new Date(work_time_ts);
  let home_time_ts = date + ' ' + home_time;
      home_time_ts = new Date(home_time_ts);
  let worked = home_time_ts.getTime() - work_time_ts.getTime();
      worked = worked / (1000 * 60 * 60);
      worked = Math.round(worked * Math.pow(10, 2)) / Math.pow(10, 2);
  let mid_night_hour = date + ' 22:00';
      mid_night_hour = new Date(mid_night_hour);
  let time_payment = 1100; //時給1,100円
  let amount = Math.floor(worked * time_payment);
  let invalid_hour = date + ' ' + '00:00';
      invalid_hour = new Date(invalid_hour);
  if(work_time_ts.getTime() == home_time_ts.getTime()) {
    if(work_time_ts.getTime() == invalid_hour.getTime() && home_time_ts.getTime() == invalid_hour.getTime()) {
      amount = null;
    }
    else {
      amount = 0;
    }
  }
  else if(work_time_ts.getTime() > home_time_ts.getTime()) {
    amount = 0;
  }
  // 正規の出退勤はここを読み込む
  else if(home_time_ts.getTime() > mid_night_hour.getTime()) {
    let after_ten = home_time_ts.getTime() - mid_night_hour.getTime();
        after_ten = after_ten / (1000 * 60 * 60);
        // ↓ 時刻1:00(1時間単位)以下.. 0:56、等半端な時間だと端数が出るのでそれを小数第二位で四捨五入するロジック
        after_ten = Math.round(after_ten * Math.pow(10, 2)) / Math.pow(10, 2);
        // ↑ 時刻1:00(1時間単位)以下.. 0:56、等半端な時間だと端数が出るのでそれを小数第二位で四捨五入するロジック
        midnight_payment = time_payment * 0.25 * after_ten;
        amount = Math.floor(worked * time_payment + midnight_payment);
  }
  let query_amount = {};
      query_amount['amount'] = amount;
      query_amount['user_no'] = $('#user_no').val();
      query_amount['work_date'] = date;
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/actual_work_sql_data.php?mode=regist_salary',
    data: query_amount,
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
  console.log('稼働時間：' + worked);
  $('#amount').html(amount);
}

//checkBoxレ点でpush通知登録
function scheduleDateSend() {
  let query = formDataTemplate();
      query['scheduleDate'] = $('input[name="push_set"]').prop('checked');
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=schedule_date',
    data: query,
    dataType: 'html'
  })
  .then(
    (data)=> {
      console.log(data);
      // push_set();
    },
    (jgXHR, testStatus, errorThrown)=> {
      console.log(jgXHR);
      console.log(testStatus);
      console.log(errorThrown);
    }
  )
}

function push_set() {
  const query = formDataTemplate();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=push_set',
    data: query,
    dataType: 'json'
  })
  .then(
    (data)=> {
      console.log(data);
      if(data.push_check.push_status == true) {
        $('input[name="push_set"]').prop('checked', true);
      }
      else {
        $('input[name="push_set"]').prop('checked', false);
      }
    },
    (jgXHR, textStatus, errorThrown)=> {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
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
      console.log(query);
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=set_list_shift',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      $('#work_time').val(data.user.work_time);
      $('#home_time').val(data.user.home_time);
      $('#user_no').val(data.user_no.no);
      salary([[data.user.work_time, data.user.home_time], date])
      $('#task_contents').html(data.task.schedule);
      $('#task_contents').css({
        'color': 'white'
      });
      replaceLink();
      date = date.slice(5, 10);
      $('#schedule_date_label').html(date + ' に通知メールを送信：');
      push_set();
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
    //url→リンク変換
    $(this).html($(this).html().replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,
    '<a href="$1" target="_blank" rel="noopener noreferrer" style="word-break: break-all;">$1</a>'));
    //メアド→リンク変換
    $(this).html($(this).html().replace(/((?:\w+\.?)*\w+@(?:\w+\.)+\w+)/gi, '<a href="mailto:$1">$1</a>'));
  });
}

//出勤記録
function workTime() {
  const query = formDataTemplate();
  const date = query['work_date'];
  const work_time = query['work_time'];
  const home_time = query['home_time'];
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=work_time',
    data: query,
    dataType: 'html'
  })
  .then(
    function(data) {
      console.log(data);
      salary([[work_time, home_time], date]);
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
  const date = query['work_date'];
  const work_time = query['work_time'];
  const home_time = query['home_time'];
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/sql_data.php?mode=home_time',
    data: query,
    dataType: 'html'
  })
  .then(
    function(data) {
      console.log(data);
      salary([[work_time, home_time], date]);
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
      date = date.slice(5, 10);
      $('#schedule_date_label').html(date + ' に通知メールを送信：');
      salary([[data.user_data.work_time, data.user_data.home_time], data.user_data.work_date]);
      push_set();
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
          date = date.slice(5, 10);
          $('#schedule_date_label').html(date + ' に通知メールを送信：');
          salary([[data.user_data.work_time, data.user_data.home_time], query2['work_date']]);
          push_set();
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