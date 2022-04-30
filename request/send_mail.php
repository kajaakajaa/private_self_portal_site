<?php

$mode = $_GET['mode'];
switch($mode) {
  case 'regist_info' :
    mb_language("Japanese"); 
    mb_internal_encoding("UTF-8");
    $to = $_POST['user_name'];
    $subject = "【TASK】登録完了のお知らせ。";
    $message = $_POST["user_name"] . "さん。\n"
      . 'この度は本アプリへのご登録ありがとうございます。' . "\n\n"
      . '下記urlよりログイン画面へお進み下さい。' . "\n"
      . 'https://kajaaserver.com/self_portal_site_private/registration/sign_in';
    $headers = 'From: 登録完了のお知らせ<info@kajaaserver.com>';
    mb_send_mail($to, $subject, $message, $headers);
    var_dump(mb_send_mail()->errorInfo());
  break;
  
  case 'sign_info' :
    mb_language("Japanese");
    mb_internal_encoding('UTF-8');
    $to = $_POST['user_name'];
    $subject = "【TASK】へログインのお知らせ";
    $message = "【TASK】へログイン致しました。\n\n"
      . "※万一当メールに身に覚えが無ければ、お手数ではございますが\n"
      . "下記アドレス迄ご連絡下さい。\n\n"
      . "【お問合せ】info@kajaaserver.com";
    $headers = 'From: ログイン確認致しました<info@kajaaserver.com>';
    mb_send_mail($to, $subject, $message, $headers);
  break;
}