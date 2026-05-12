<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$setting_push = htmlspecialchars($_REQUEST['setting']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";

if($str_mp == $masterpassword && $reg_id)
{
	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
		$youngcart_origin = $row_1['gpr_setting_youngcart'];
		$val_youngcart = 'N';
		if($setting_push == "true") $val_youngcart = 'Y';

		if($youngcart_origin != $val_youngcart)
		{		
			$sql = " update g5_gnupushapp_gcmregid
						set gpr_setting_youngcart = '{$val_youngcart}'
						where gpr_reg_id = '{$reg_id}' ";
			sql_query($sql);

			
			sql_query(" update g5_gnupushapp_subscribe 
				set gss_post_subscribe_onoff = '{$val_youngcart}'
				where gss_reg_id = '{$reg_id}' and gss_is_youngcart = 'Y' ", true);		
		}

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