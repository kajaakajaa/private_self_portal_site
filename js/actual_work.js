$(()=> {
  setListActualWork();

  //modal
  //開く
  $(document).on('click', '.modal-target', () => {
    let modal = '';
        modal = '<div id="modal">'
              +   '<div>警告'
              +     '<span id="close">'
              +       '<img src=/self_portal_site_private/images/close.png width="20px" height="20px" alt="閉じる" title="閉じる">'
              +     '</span>'
              +   '</div>'
              +   '<div>&#x203B;これはモーダルです</div>'
              + '</div>';
    $('#overlay').html(modal);
    $('#overlay').fadeIn();
    return false;
  });
  $(document).on('click', '#modal', ()=> {
    return false;
  });
  //閉じる
  $(document).on('click', '#overlay, #close', ()=> {
    $('#overlay').html('');
    $('#overlay').fadeOut();
    return false
  });
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
            content += '<li><a class="modal-target">' + value.work_month + '&nbsp;月</a></li>';
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