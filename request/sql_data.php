<?php
include_once('../../config/db_connect.php');

$mode = $_GET['mode'];
switch($mode) {
  case 'work_time':
    $workTime = $_POST['work_time'];
    if($workTime)
      $sql = <<<EOF
        UPDATE
          
EOF;
  break;
}