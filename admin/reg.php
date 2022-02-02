<?php
include_once('message_out.php');
include_once('session_check.php');
include_once('../config/db_connect.php');
include_once('check_input.php');

//リクエストパラメーターの取得
$no = $_REQUEST['no'];
$key = $_REQUEST['key'];
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$last_name = $_REQUEST['last_name'];
$first_name = $_REQUEST['first_name'];

if($_REQUEST['mode'] == 'exe') {
  // セッションの開始
  $rtn = session_check();
  if($rtn == FALSE) {
    message_out('セッションが無効です', 'null');
  }
  // 入力チェック
  $messages = check_input(NULL, $password, $last_name, $first_name);
  if(count($messages) == 0) {
    // レコードの更新
    $status = '1'; //仮会員を表すステータス
    $dbh = db_connect();
    $rtn = db_access('upd_member', $dbh, 'no', $no, $username, $password, $last_name, $first_name, $status);
    // 処理結果の出力
    if($rtn === FALSE) {
      message_out('データベースアクセスエラー[upd_member]', 'menu');
    }
    if($rtn == TRUE) {
      message_out('登録出来ました。', 'null');
    }
    else if($rtn == 0) {
      message_out('対象レコードは既に削除されています。', 'menu');
    }
  }
}
else {
  $dbh = db_connect();
  $arr = db_access('search_member', $dbh, 'no', $no);
  if($arr == FALSE) {
    message_out('データベースアクセスエラー[search_member_all]', 'menu');
  }
  if(count($arr) == 0) {
    message_out('データはありません', 'menu');
  }
  if($key != $arr[0]['password']) {
    message_out('会員登録はできません', 'null');
  }
  $username = $arr[0]['username'];
  
  // セッションの開始
  session_idset();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー登録画面</title>
</head>
<body>
  <form action="/self_portal_site/admin/reg.php?mode=exe" method="post">
    <?php
    if(count($messages) != 0) {
      print '<p>&#9679;入力エラー</p><br/>';
      foreach($messages as $msg) {
        print $msg . '<br/>';
      }
      print '<hr/>';
    }
    ?>
    <table id="regist_view_table">
      <tr>
        <td>ユーザ名</td>
        <td><?php echo $username; ?></td>
      </tr>
      <tr>
        <td>パスワード</td>
        <td>
          <input type="text" name="password" value="<?php echo $password; ?>" size="12" maxsize="8">
        </td>
      </tr>
      <tr>
        <td>姓</td>
        <td>
          <input type="text" name="last_name" vlaue="<?php echo $last_name; ?>" size ="50" maxsize="255">
        </td>
      </tr>
      <tr>
        <td>名</td>
        <td>
          <input type="text" name="first_name" vlaue="<?php echo $first_name; ?>" size ="50" maxsize="50">
        </td>
      </tr>
    </table>
    <input type="hidden" name="no" value="<?php echo $no; ?>" />
    <input type="hidden" name="username" value="<?php echo $username; ?>" />
    <br/>
    <input type="submit" value="登録する">
  </form>
</body>
</html>