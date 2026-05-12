<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$mb_nick = htmlspecialchars($_REQUEST['mb_nick']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);
$count = 0;
$mcount = 0;
$gcount = 0;
$array = array();
$array['response'] = "fail";

if($str_mp == $masterpassword)
{

	$row_result = sql_query(" select * from g5_gnupushapp_chatroom ");

	for ($i=0; $row=sql_fetch_array($row_result); $i++)
	{
		$use_profile = "false";
		$profile_link = "none";
		$sync = "false";
		$mb_id = "none";

		//회원여부 판단
		$device = get_device_info_by_regid($row['gpcr_reg_id']);
		if($device['gpr_sync'] != "N")
		{
			$sync = "true";
			$mb_id = $device['gpr_mb_id'];
			if($gnu_config['build_sort'] == 'A')
			{
				$mb_icon_path = G5_DATA_PATH.'/apms/photo/'.substr($mb_id,0,2).'/'.$mb_id.'.jpg';
				$mb_icon_url  = G5_DATA_URL.'/apms/photo/'.substr($mb_id,0,2).'/'.$mb_id.'.jpg';
			}else{
				$mb_icon_path = G5_DATA_PATH.'/member/'.substr($mb_id,0,2).'/'.$mb_id.'.gif';
				$mb_icon_url  = G5_DATA_URL.'/member/'.substr($mb_id,0,2).'/'.$mb_id.'.gif';
			}
			if(file_exists($mb_icon_path)){
				$use_profile = "true";
				$profile_link = $mb_icon_url;
			}
		}

		$thisarray = array("mb_nick" => $row['gpcr_nick'], "sync" => $sync, "use_profile" => $use_profile, "profile_link" => $profile_link, "mb_id" => $mb_id);
		if($mb_nick != $row['gpcr_nick'])
		{
			if($sync == "true")
			{
				$strcount = $mcount;
				$array["mnick".$strcount] = urlencode(json_encode($thisarray));
				$mcount++;

			}
			else
			{
				$strcount = $gcount;
				$array["gnick".$strcount] = urlencode(json_encode($thisarray));
				$gcount++;
			}
		}

	}

	$row_count = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom ");

	$count = $row_count['cnt'];

	if($count > 0)
	{
		$array['response'] = "ok";
	}
	else
	{
		$array['response'] = "none";
	}

	$array['count'] = $count;
	$array['mcount'] = $mcount;
	$array['gcount'] = $gcount;


}

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>