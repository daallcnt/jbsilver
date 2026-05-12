<?php
include_once('../common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$sql = " delete from g5_gnupushapp_errorlog ";
sql_query($sql);

goto_url('error.php', false);

?>
