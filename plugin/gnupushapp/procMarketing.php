<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$marketing = htmlspecialchars($_REQUEST['marketing']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";

if($str_mp == $masterpassword)
{
	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
		if($marketing == "true")
		{
			$setting_val = "Y";
		}
		else
		{
			$setting_val = "N";
		}
		

		$sql = " update g5_gnupushapp_gcmregid
					set gpr_setting_marketing = '{$setting_val}'
					where gpr_reg_id = '{$reg_id}' ";
		sql_query($sql);
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