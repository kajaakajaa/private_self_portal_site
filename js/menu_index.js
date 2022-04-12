$(()=> {
  setListMenu();

  //menuの追加カテゴリー登録(動的な要素の値を取得->オンロード内on.click使用)
  $('#add_menu_btn').on('click', ()=> {
    const count = checkError();
    let query = {};
        query['category_name'] = $('#add_menu').val();
        query['user_no'] = $('#user_no').val();
    if(count['duplicate'] == 0 && count['empty'] == 0) {
      $.ajax({
        type: 'POST',
        url: '/request/menu_sql_data.php?mode=add_menu',
        data: query,
        dataType: 'html'
      })
      .then(
        function(data) {
          console.log(data);
          $('#menu_modal').modal('hide');
          $('#add_menu').val('');
          $('.error-messages').html('');
          setListMenu();
        },
        function(jgXHR, textStatus, errorThrown) {
          console.log(jgXHR);
          console.log(textStatus);
          console.log(errorThrown);
        }
      );
    }
  });
})

//オンロード時のデフォルトview
function setListMenu() {
  let query = {};
      query['user_no'] = $('#user_no').val();
  let userNo = $('#user_no').val();
  $.ajax({
    type: 'POST',
    url: '/request/menu_sql_data.php?mode=set_list_menu',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      let contents = '';
      $.each(data.menu, (key, value)=> {
        contents += '<li><a class="menu-index-list" href="/menu_contents.php?menu_no=' + value.menu_no + '&user_no=' + userNo + '">'
          + value.category_name + '</a></li>';
      });
      $('#menu_list').html(contents);
    },
    function(jgXHR, textStatus, errorThrown) {
      console.log(jgXHR);
      console.log(textStatus);
      console.log(errorThrown);
    }
  );
}

//menu内の重複チェック
function checkError() {
  let count = {};
      count['duplicate'] = 0;
      count['empty'] = 0;
  let addedMenu = $('#menu_list li a');
  if(addedMenu.length) {
    $.each(addedMenu, (key, value)=> {
      if($(value).html() == $('#add_menu').val()) {
        $('.error-messages').html('&#x203B;既に登録されております');
        count['duplicate']++;
      }
    });
  }
  if($('#add_menu').val() == '') {
    $('.error-messages').html('&#x203B;カテゴリー名を入力して下さい');
    count['empty']++;
  }
  return count;
}