<?php
include_once('./_common.php');

$gnu_config = get_gnupushapp_config();

$gp_bo_table = htmlspecialchars($_POST['bo_table']);
$gp_wr_id = htmlspecialchars($_POST['wr_id']);
$gp_co_id = htmlspecialchars($_POST['co_id']);
$masterpassword = htmlspecialchars($_POST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword)
{
	$row = sql_fetch(" select * from {$g5['apms_response']} where bo_table = '{$gp_bo_table}' and wr_id = '{$gp_wr_id}' and co_id = '{$gp_co_id}'", false);
	if($row['id']) {
		apms_response_act($row['id']);
	}
}

exit();

?>