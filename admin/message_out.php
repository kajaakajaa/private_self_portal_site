<?php
// メッセージ出力関数
function message_out($msg, $next) {
  
  // 画面遷移用のリンク
  switch($next) {
    case 'login':
      $href = '<a href="/self_portal_site/admin/login.php">ログイン画面へ</a>';
    break;

    case 'menu':
      $href = '<a href="/self_portal_site/admin/menu.php">メニュー画面へ</a>';
    break;

    case 'null':
    break;
  }

  // 画面の出力
  $out = <<<EOF
    <html>
    <head>
    <title>メッセージ画面</title>
    </head>
    <body>
    $msg<br/>
    <hr/>
    $href
    </body>
    </html>
EOF;
  print $out;
die();
}
?>