$(()=> {
  setListCategory();

  //category内容編集
  $('#edit_category_btn').on('click', ()=> {
    $('#category_contents').hide();
    $('#category_contents_wrapper').show();
    $('#edit_category_close').show();
    $('textarea[name="edit_category_contents"]').focus();
  });

  //category編集フォーム更新し閉じる
  $('#close_category').on('click', ()=> {
    const query = getFormData();
    console.log(query);
    $.ajax({
      type: 'POST',
      url: '/self_portal_site/request/category_sql_data.php?mode=regist_category_contents',
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
    $('#category_contents_wrapper').hide();
    $('#edit_category_close').hide();
    $('#category_contents').show();
    setListCategory();
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
      $('#category_contents').html(data.contents);
      $('#edit_category_contents').val(data.contents_nobr);
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