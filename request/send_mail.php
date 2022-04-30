<?php
echo '<pre>';
print_r($_POST);
echo '</pre>';

mb_language("Japanese"); 
mb_internal_encoding("UTF-8");
$to = "rwqxh8703@yahoo.co.jp";
$subject = "問合せ";
$message = $_POST["name"];
$headers = 'From: test<info@aaaa.com>';
mb_send_mail($to, $subject, $message, $headers);
?>