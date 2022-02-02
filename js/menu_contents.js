$(()=> {
  setListCategory();

  //category内容編集
  $('#edit_category_btn').on('click', ()=> {
    let contents = $('#category_contents').html();
    $('#edit_category_contents').val(contents);
    $('#category_contents').hide();
    $('#category_contents_wrapper').show();
    $('#edit_category_close').show();
    $('textarea[name="edit_category_contents"]').focus();
  });

  //カテゴリー名の変更
  $('#change_name_btn').on('click', ()=> {
    let query = getFormData();
        query['change_category_name'] = $('#name_change').val();
    let count = {};
        count['duplicate'] = 0;
        count['empty'] = 0;
    $.ajax({
      type: 'POST',
      url: '/self_portal_site/request/category_sql_data.php?mode=check_error',
      data: query,
      dataType: 'json'
    })
    .then(
      function(data) {
        console.log(data);
        $.each(data.category, (key, value)=> {
          if(value.category_name == $('#name_change').val()) {
            $('.error-messages').html('&#x203B;既に登録されております');
            count['duplicate']++;
          }
        });
        if($('#name_change').val() == '') {
          $('.error-messages').html('&#x203B;カテゴリー名を入力して下さい');
          count['empty']++;
        }
        if(count['duplicate'] == 0 && count['empty'] == 0) {
          $.ajax({
            type: 'POST',
            url: '/self_portal_site/request/menu_sql_data.php?mode=change_menu_name',
            data: query,
            dataType: 'html'
          })
          .then(
            function(data) {
              console.log(data);
              setListCategory();
            },
            function(jgXHR, textStatus, errorThrown) {
              console.log(jgXHR);
              console.log(textStatus);
              console.log(errorThrown);
            }
          );
        }
      },
      function(jgXHR, textStatus, errorThrown) {
        console.log(jgXHR);
        console.log(textStatus);
        console.log(errorThrown);
      }
    );
  });

  //category名変更フォームを閉じる
  $('.name-change-close').on('click', ()=> {
    setListCategory();
  });

  //category編集フォーム更新し閉じる
  $('#close_category').on('click', ()=> {
    const query = getFormData();
    $.ajax({
      type: 'POST',
      url: '/self_portal_site/request/category_sql_data.php?mode=regist_category_contents',
      data: query,
      dataType: 'html'
    })
    .then(
      function(data) {
        console.log(data);
        $('#category_contents_wrapper').hide();
        $('#edit_category_close').hide();
        $('#category_contents').show();
        setListCategory();
      },
      function(jgXHR, textStatus, errorThrown) {
        console.log(jgXHR);
        console.log(textStatus);
        console.log(errorThrown);
      }
    );
  });

  //category編集フォームを閉じる(×ボタン)
  $('#edit_category_close').on('click', ()=> {
    setListCategory();
    $('#category_contents_wrapper').hide();
    $('#edit_category_close').hide();
    $('#category_contents').show();
  });

  //categoryを削除
  $('#delete_category_contents').on('click', ()=> {
    const query = getFormData();
    $.ajax({
      type: 'POST',
      url: '/self_portal_site/request/category_sql_data.php?mode=delete_category_contents',
      data: query,
      dataType: 'html'
    })
    .then(
      function(data) {
        console.log(data);
        setListCategory();
        backTop();
      },
      function(jgXHR, textStatus, errorThrown) {
        console.log(jgXHR);
        console.log(textStatus);
        console.log(errorThrown);
      }
    );
  });
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

//テンプレートデータ
function getFormData() {
  let query = {};
      query['user_no'] = $('#user_no').val();
      query['menu_no'] = $('#menu_no').val();
      query['contents'] = $('#edit_category_contents').val();
  return query;
}

//オンロード時に読み込まれる
function setListCategory() {
  const query = getFormData();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/category_sql_data.php?mode=set_list_category',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
        $('#category_name').html(data.menu.category_name);
        $('#category_contents').html(data.contents_nobr);
        $('#edit_category_contents').val(data.category.contents);
        $('.error-messages').html('');
        $('#name_change_modal').modal('hide');
        $('#name_change').val(data.menu.category_name);
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

function backTop() {
  window.location.href = '/self_portal_site/index.php';
}

//カテゴリー名の重複/空欄チェック
function checkError() {
  const query = getFormData();
  let count = {};
      count['duplicate'] = 0;
      count['empty'] = 0;
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/category_sql_data.php?mode=check_error',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      $.each(data.category, (key, value)=> {
        if(value.category_name == $('#name_change').val()) {
          $('.error-messages').html('&#x203B;既に登録されております');
          count['duplicate']++;
        }
      });
      if($('#name_change').val() == '') {
        $('.error-messages').html('&#x203B;カテゴリー名を入力して下さい');
        count['empty']++;
      }
      $('#duplicate_check').val(count['duplicate']);
      $('#empty_check').val(count['empty']);
    },
    function(jgXRH, textStatus, errorThrown) {
      console.log(jgXRH);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}