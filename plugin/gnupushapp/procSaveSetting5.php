<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$chatpush = htmlspecialchars($_REQUEST['chatpush']);
$iphonesound = htmlspecialchars($_REQUEST['iphonesound']);
$board_keyword = htmlspecialchars($_REQUEST['board_keyword']);
$youngcart_keyword = htmlspecialchars($_REQUEST['youngcart_keyword']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";

if($str_mp == $masterpassword && $reg_id)
{
	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
		if(!$chatpush) $chatpush = "true";

		$val_chat = "N";
		if($chatpush == "true") $val_chat = "Y";

		$other_setting_array = array($iphonesound, $board_keyword, $youngcart_keyword,$chatpush);
		$osa = serialize($other_setting_array);

		$sql = " update g5_gnupushapp_gcmregid
					set gpr_other_setting = '{$osa}',
					gpr_setting_chat = '{$val_chat}'
					where gpr_reg_id = '{$reg_id}' ";
		sql_query($sql);

		sql_query(" update g5_gnupushapp_subscribe 
					set gss_other_setting = '{$osa}'
					where gss_reg_id = '{$reg_id}' ", true);

		$response = "ok";
	}

}

$array = array("response" => $response);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>