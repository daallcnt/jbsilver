<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();
$masterpassword = htmlspecialchars($_POST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";
$random = "none";

$delete_cnt = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios WHERE gpcr_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ");
if($delete_cnt['cnt'] > 0)
{
	sql_query("DELETE FROM g5_gnupushapp_chatroom_ios WHERE gpcr_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ", true);
}

if($str_mp == $masterpassword && $_SESSION['reg_id'])
{

	$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$_SESSION['reg_id']}' ");

	if($row_tmp['cnt'] != 0)
	{
		$response = "ok";
		$row_tmp2 = sql_fetch(" select * from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$_SESSION['reg_id']}' ");
		$random = $row_tmp2['gpci_random'];
	}
}

$array = array("response" => $response, "random" => $random);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();



?>