$(()=> {
  setListMenu();

  //menuの追加カテゴリー登録(動的な要素の値を取得->オンロード内on.click使用)
  $(document).on('click', '#add_menu_btn', ()=> {
    const duplicateCount = checkDuplicate();
    let query = {};
        query['category_name'] = $('#add_menu').val();
        query['user_no'] = $('#user_no').val();
    if(duplicateCount > 0) {
      $('#duplicated_message').html('&#x203B;既に登録されております');
    }
    else {
      $.ajax({
        type: 'POST',
        url: '/self_portal_site/request/menu_sql_data.php?mode=add_menu',
        data: query,
        dataType: 'html'
      })
      .then(
        function(data) {
          console.log(data);
          $('#menu_modal').modal('hide');
          $('#add_menu').val('');
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

function setListMenu() {
  let query = {};
      query['userNo'] = $('#user_no').val();
  $.ajax({
    type: 'POST',
    url: '/self_portal_site/request/menu_sql_data.php?mode=set_list_menu',
    data: query,
    dataType: 'json'
  })
  .then(
    function(data) {
      console.log(data);
      let contents = '';
      $.each(data.menu, (key, value)=> {
        contents += '<li><a class="menu-index-list">' + value.category_name + '</a></li>';
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
function checkDuplicate() {
  let duplicateCount = 0;
  let addedMenu = $('#menu_list li a');
  if(addedMenu.length) {
    $.each(addedMenu, (key, value)=> {
      if($(value).html() == $('#add_menu').val()) {
        duplicateCount++;
      }
      else {
        $('#duplicated_message').html('');
      }
    });
  }
  return duplicateCount;
}