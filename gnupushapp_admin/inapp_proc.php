<?php
include_once('../common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$sql = " delete from g5_gnupushapp_inapp where gin_status = 'start' and gin_regdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), INTERVAL -1 DAY) ";
sql_query($sql);


goto_url('inapp.php', false);

?>
