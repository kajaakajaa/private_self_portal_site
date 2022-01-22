$(()=> {
  $('#datepicker').datepicker().datepicker('setDate', 'today');
  setListShift();
});

//formデータ
function formDataTemplate() {
  let query = {};
  query['work_date'] = $('#datepicker').val();
  query['user_no'] = $('#user_no').val();
  query['work_time'] = $('#work_time').val();
  query['home_time'] = $('#home_time').val();
  return query;
}

//オンロード時に取得するデフォルト値
function setListShift() {
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
        $('#user_no').val(value.no);
        $('#work_time').val(value.work_time);
        $('#home_time').val(value.home_time);
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
      setListShift();
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
      setListShift();
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
      }
      else {
        $.each(data.user, (key, value)=> {
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

function getBeforeAfterDay(num) {
  let query = formDataTemplate();
      query['other_day'] = num;
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/sql_data.php?mode=get_before_after_day',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      let date = data.date;
      if(data.user == null) {
        $('#datepicker').val(date);
        $('#work_time').val('');
        $('#home_time').val('');
      }
      else {
        $.each(data.user, (key, value)=> {
          $('#datepicker').val(date);
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