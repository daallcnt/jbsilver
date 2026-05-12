<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_POST['reg_id']);
$masterpassword = htmlspecialchars($_POST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword)
{
	$row_info = get_device_info_by_regid($reg_id);
	if($row_info['gpr_reg_id']){

		$setting_newpost = 'N';
		$setting_newcom = 'N';
		$setting_notice = 'N';
		$setting_youngcart = 'N';
		$setting_youngcart_all = 'N';
		if($gnu_config['choose_board_s'] == "D" || $gnu_config['choose_board_s'] == "Y" || $gnu_config['choose_board_s'] == "F" || $gnu_config['choose_board_s'] == "F2" || $gnu_config['choose_board_s'] == "C") $setting_newpost = 'Y';
		if($gnu_config['choose_board_s'] == "D" || $gnu_config['choose_board_s'] == "F" || ($gnu_config['choose_board_s'] == "C" && $gnu_config["subscribe_comments"] == "Y")) $setting_newcom = 'Y';
		if(is_array($gnu_config['setting_f']) && in_array("g", $gnu_config['setting_f'])) $setting_notice = 'Y';
		if($gnu_config["youngcart_category_boolean"] == "Y") $setting_youngcart_all = 'Y';		
		if($gnu_config["use_youngcart"] == "Y") $setting_youngcart = 'Y';
		$setting_reply = 'N';
		$setting_mypost_com = 'N';
		$setting_mycom_com = 'N';
		$setting_mycom_tail = 'N';
		$setting_message = 'N';
		$setting_mention = 'N';
		$setting_recommendation = 'N';
		$setting_marketing = 'N';
		$setting_chat = 'Y';

		$badgeN = 0;
		
		$gpr_social = 'none';
		$sql = " update g5_gnupushapp_gcmregid
					set gpr_mb_id = null,
					gpr_sync = 'N',
					gpr_badge = '{$badgeN}',
					gpr_social = '{$gpr_social}',
					gpr_last_login = '".G5_TIME_YMDHIS."',
					gpr_setting_newpost = '{$setting_newpost}',
					gpr_setting_newcom = '{$setting_newcom}',
					gpr_setting_myreply = '{$setting_reply}',
					gpr_setting_mypost_com = '{$setting_mypost_com}',
					gpr_setting_mycom_com = '{$setting_mycom_com}',
					gpr_setting_mycom_tail = '{$setting_mycom_tail}',
					gpr_setting_notice = '{$setting_notice}',
					gpr_setting_message = '{$setting_message}',
					gpr_setting_mention = '{$setting_mention}',
					gpr_setting_recommendation = '{$setting_recommendation}',
					gpr_setting_marketing = '{$setting_marketing}',
					gpr_setting_youngcart = '{$setting_youngcart}',
					gpr_setting_chat = '{$setting_chat}',
					gpr_setting_youngcart_all = '{$setting_youngcart_all}'
					where gpr_reg_id = '{$reg_id}' ";
		sql_query($sql);

		$sql = " delete from g5_gnupushapp_subscribe where gss_reg_id = '{$reg_id}' ";
		sql_query($sql);

		set_session('reg_id', $reg_id);

		setdefaultsettingboard($reg_id, $row_info['gpr_sort']);
	}
}

$array = array("sessionok" => "ok");

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;

exit();

?>