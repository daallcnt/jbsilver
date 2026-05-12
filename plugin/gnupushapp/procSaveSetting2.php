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
		$setting_newpost_origin = $row_1['gpr_setting_newpost'];
		$setting_newcom_origin = $row_1['gpr_setting_newcom'];

		$setting = explode("-", $setting_push);

		$i = 0;

		$setting_newpost = 'N';
		$setting_newcom = 'N';
		$setting_reply = 'N';
		$setting_mypost_com = 'N';
		$setting_mycom_com = 'N';
		$setting_mycom_tail = 'N';
		$setting_notice = 'N';
		$setting_message = 'N';
		$setting_mention = 'N';
		$setting_recommendation = 'N';
		$setting_marketing = 'N';

		foreach($setting as $val)
		{

			if($i == 0 && $val == "true")
			{
				$setting_newpost = 'Y';
			}
			if($i == 1 && $val == "true")
			{
				$setting_newcom = 'Y';
			}
			if($i == 2 && $val == "true")
			{
				$setting_reply = 'Y';
			}
			if($i == 3 && $val == "true")
			{
				$setting_mypost_com = 'Y';
			}
			if($i == 4 && $val == "true")
			{
				$setting_mycom_com = 'Y';
			}
			if($i == 5 && $val == "true")
			{
				$setting_mycom_tail = 'Y';
			}
			if($i == 6 && $val == "true")
			{
				$setting_notice = 'Y';
			}
			if($i == 7 && $val == "true")
			{
				$setting_message = 'Y';
			}
			if($i == 8 && $val == "true")
			{
				$setting_mention = 'Y';
			}
			if($i == 9 && $val == "true")
			{
				$setting_recommendation = 'Y';
			}
			if($i == 10 && $val == "true")
			{
				$setting_marketing = 'Y';
			}

			$i++;
		}

		sql_query(" update g5_gnupushapp_subscribe 
				set gss_post_comment_subscribe = '{$setting_newcom}',
				gss_post_subscribe_onoff = '{$setting_newpost}'
				where gss_reg_id = '{$reg_id}' and gss_is_youngcart = 'N' ", true);

		//reg_id db에 입력
		$sql = " update g5_gnupushapp_gcmregid
				set gpr_setting_newpost = '{$setting_newpost}',
				gpr_setting_newcom = '{$setting_newcom}',
				gpr_setting_myreply = '{$setting_reply}',
				gpr_setting_mypost_com = '{$setting_mypost_com}',
				gpr_setting_mycom_com = '{$setting_mycom_com}',
				gpr_setting_mycom_tail = '{$setting_mycom_tail}',
				gpr_setting_notice = '{$setting_notice}',
				gpr_setting_message = '{$setting_message}',
				gpr_setting_mention = '{$setting_mention}',
				gpr_setting_recommendation = '{$setting_recommendation}',
				gpr_setting_marketing = '{$setting_marketing}'

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