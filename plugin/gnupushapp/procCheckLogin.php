<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$masterpassword = htmlspecialchars($_POST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword)
{
	$sync = "N";
	$nick_name = "none";
	$use_profile = "false";
	$profile_link = "none";

	if ($is_member) { 
		$reg_id = get_session('reg_id');
		$row_1 = get_device_info_by_regid($reg_id);

		if($row_1)
		{
			$sync = $row_1['gpr_sync'];
			$mb_id_gnupush = $row_1['gpr_mb_id'];
		}
		$now_login = "true";
		if($sync != "N"){
			$mb_icon_url = get_gnu_profile_image($mb_id_gnupush);
			if($mb_icon_url){
				$use_profile = "true";
				$profile_link = $mb_icon_url;
			}
		}
		$member = get_member($_SESSION['ss_mb_id']);
		$nick_name = $member['mb_nick'];
		$now_login = "Y";
	}
	else
	{
		$now_login = "N";
	}

	$array = array("is_logged_in" => $now_login, "nick_name" => $nick_name, "sync" => $sync, "use_profile" => $use_profile, "profile_link" => $profile_link);

	$json = "";

	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;

}

exit();

?>