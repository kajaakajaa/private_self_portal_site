$(()=> {
  setListActualWork();
});

function setListActualWork() {
  let query = {};
      query['year'] = Number($('#actual_work_header').html().slice(0, 4));
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
            content += '<li><a class="modal-target" id="' + i + '_month">' + value.work_month + '&nbsp;月</a></li>';
            count++;
          }
        });
      }
      $('#actual_work_body > ul').html(content);
      modal();
    },
    (jgXHR, textStatus, errorThrown)=> {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

function modal() {
  //開く
  $.each($('.modal-target'), (index, value)=> {
    $(value).on('click', ()=> {
      let query = {};
          query['month'] = $(value).html().slice(0, 1);
      $.ajax({
        type: 'POST',
        url: '/self_portal_site_private/request/actual_work_sql_data.php?mode=detail_salary',
        data: query,
        dataType: 'json'
      })
      .then(
        (data)=> {
          console.log(data);
          let headerTitle = $(value).html();
          let totalSalary = 0;
          let bodyContents = '<div class="total-salary">合計：<span style="font-weight: bold;">' + totalSalary + '</span>&nbsp;円</div>';
              bodyContents += '<table>';
              $.each(data.user, (index, value)=> {
                if(value.salary == null) {
                    bodyContents +=
                      '<tr></tr>';
                }
                else if(value.salary == 0) {
                  if((value.work_time == value.home_time)) {
                    bodyContents += 
                        '<tr>'
                      +   '<td>' + value.work_day + '日' + value.work_date + '</td>'
                      +   '<td>' + value.work_time + '<span class="work-home">~</span>(出勤)</td>'
                      + '</tr>';
                  }
                  else {
                      bodyContents +=
                      '<tr>'
                    +   '<td>' + value.work_day + '日' + value.work_date + '</td>'
                    +   '<td style="color: red;">休み</td>'
                    + '</tr>';
                  }
                }
                else {
                  bodyContents +=
                      '<tr>'
                  +     '<td>' + value.work_day + '日' + value.work_date + '</td>'
                  +     '<td>' + value.work_time + '<span class="work-home">~</span>' + value.home_time + '</td>'
                  +     '<td>' + value.salary + '円</td>'
                  +   '</tr>';
                }
                totalSalary += Number(value.salary);
              });
              bodyContents += '</table>';
          let modal = '';
              modal = '<div id="modal">'
                    +   '<div class="modalHeader">' + headerTitle
                    +     '<span id="close">'
                    +       '<img src=/self_portal_site_private/images/close.png width="20px" height="20px" alt="閉じる" title="閉じる">'
                    +     '</span>'
                    +   '</div>'
                    +   '<div class="modalBody">' + bodyContents + '</div>'
                    + '</div>';
          $('#overlay').html(modal);
          $('#overlay').fadeIn();
          $('#modal').css({
            'opacity': '1',
            'transform': 'translateY(0)',
            'transition': '.5s'
          });
          $('.total-salary > span').html(totalSalary.toLocaleString());
          return false;
        },
        (jgXHR, textStatus, errorThrown)=> {
          console.log(jgXHR);
          console.log(textStatus);
          console.log(errorThrown);
        }
      );
    });
  });
  $(document).on('click', '#modal', ()=> {
    return false;
  });
  //閉じる
  $(document).on('click', '#overlay, #close', ()=> {
    $('#modal').css({
      'opacity': '0',
      'transform': 'translateY(-70px)',
      'transition': '.5s'
    });
    $('#overlay').fadeOut();
    return false
  });
}