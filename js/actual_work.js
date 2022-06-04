$(()=> {
  setListActualWork();
});

function setListActualWork() {
  let query = {};
  $.ajax({
    type: 'POST',
    url: '/self_portal_site_private/request/actual_work_sql_data.php?mode=set_list_acual_work',
    data: query,
    dataType: 'json'
  })
  .then(
    (data)=> {
      console.log(data);
      let content = '';
      for(i = 1; i <= 12; i++) {
        let count = 0;
        $.each(data.user, (index, value)=> {
          while(i == value.work_month && count == 0) {
            content += '<li><a onClick="salaryIndex()">' + value.work_month + '&nbsp;月</a></li>';
            count++;
          }
        });
      }
      $('#actual_work_body > ul').html(content);
    },
    (jgXHR, textStatus, errorThrown)=> {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}