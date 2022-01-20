$(()=> {
  setListShift();
});

//オンロード時に取得するデフォルト値
function setListShift() {
  let query = {};
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
      })
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
  let query = {};
      query['user_no'] = $('#user_no').val();
      query['work_time'] = $('#work_time').val();
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
  let query = {};
      query['user_no'] = $('#user_no').val();
      query['home_time'] = $('#home_time').val();
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