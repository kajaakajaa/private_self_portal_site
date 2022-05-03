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
  break;
  
  case 'sign_info' :
    mb_language("Japanese");
    mb_internal_encoding('UTF-8');
    $to = $_POST['user_name'];
    $subject = "【TASK】へログインのお知らせ";
    $message = "【TASK】へ" . $_POST['user_name'] . "さんがログイン致しました。\n\n"
      . "※尚、ログインに身に覚えがありましたらこのメールを無視して下さい。\n"
      . "身に覚えが無ければアカウント停止の申請をお勧め致します。\n"
      . "その際は下記問い合わせから件名より'アカウント停止'を入力し送信して下さい。\n\n"
      . "【問い合わせ】info@kajaaserver.com";
    $headers = 'From: ログイン確認を致しました<info@kajaaserver.com>';
    mb_send_mail($to, $subject, $message, $headers);
  break;
}