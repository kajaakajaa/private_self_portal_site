$(()=> {
  setListShift();
});

//formデータ
function formDataTemplate() {
  let query = {};
  query['work_time'] = $('#work_time').val();
  query['home_time'] = $('#home_time').val();
  query['edit_task'] = $('#edit_task').val();
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
      $.each(data.user_no, (key, value)=> {
        $('#user_no').val(value.no);
      });
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

//前日・翌日ボタンで表示
function getBeforeAfterDay(num) {
  let query = formDataTemplate();
      query['other_day'] = num;
      console.log(query);
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=get_before_after_day',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      $.each(data.date, (key, value)=>{
        console.log(value.work_date);
      });
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
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      $('#task_contents').html(data.task);
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

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
      $('#edit_task').val(data.task);
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}