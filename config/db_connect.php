<?php

include_once('console_log.php');
include_once('env.php');
// 5~13行.envファイル(現未使用)の読み込み用
// include_once('../vendor/autoload.php');
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// $server = $_ENV['SERVER'];
// $user = $_ENV['USER'];
// $password = $_ENV['PASSWORD'];
// $dbname = $_ENV['DBNAME'];
$server = SERVER;
$user = USER;
$password = PASSWORD;
$dbname = DBNAME;

// $server = 'localhost';
// $dbname = 'xs072153_kajaadatabase';
// $user = 'xs072153_kajaa';
// $password = 'waayassa7';

try {
  $dbh = new PDO('mysql:host=' . $server . ';dbname=' . $dbname . ';charset=utf8', $user, $password);
}
catch (PDOException $e) {
  echo $e->getMessage();
  die();
}