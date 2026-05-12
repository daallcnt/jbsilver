<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$my_nick = "";
$my_id = "";
$my_profile_link = "none";
$member_list = array();
$is_member_ = "N";
$membercount = 0;
$anonymouscount = 0;

if($gnu_config['login_session'] == 'Y'){
	$_SESSION['reg_id'] = htmlspecialchars($_REQUEST['reg_id']);
	$_SESSION['ss_mb_id'] = null;
	$is_member = false;
	$device_in_sub = get_device_info_by_regid($_SESSION['reg_id']);
	if($device_in_sub['gpr_sync'] == "Y" || $device_in_sub['gpr_sync'] == "S" || $device_in_sub['gpr_sync'] == "D"){
		$_SESSION['ss_mb_id'] = $device_in_sub['gpr_mb_id'];
		$is_member = true;
		
	}
}

if($is_member){
	$mem = get_member($_SESSION['ss_mb_id']);
	$my_nick = $mem['mb_nick'];
	$my_id = $_SESSION['ss_mb_id'];

	$mb_icon_url = get_gnu_profile_image($mem['mb_id']);
	if($mb_icon_url) $my_profile_link = $mb_icon_url;
}

$response = "fail";
if($str_mp == $masterpassword && $_SESSION['reg_id'])
{
	$response = "ok";

	$temp_my_id = substr($_SESSION['reg_id'], 30, 18);
	if($is_member){
		$is_member_ = "Y";
		$my_id = $_SESSION['ss_mb_id'];
	}else{
		$my_id = $temp_my_id;
	}

	$row_tmp = sql_fetch(" SELECT * from g5_gnupushapp_free_member where reg_id = '{$_SESSION['reg_id']}' ");
	if($row_tmp['gpf_ix'])
	{
		$gpf_ix = $row_tmp['gpf_ix'];
		sql_query(" UPDATE g5_gnupushapp_free_member 
					set lastdate = '".G5_TIME_YMDHIS."' where reg_id = '{$_SESSION['reg_id']}' ", true);
		$my_nick = $row_tmp['nickname'];
	}else{
		sql_query(" INSERT INTO g5_gnupushapp_free_member 
					set reg_id = '{$_SESSION['reg_id']}',
						mb_id = '$my_id',
						sync = '$is_member_',
						nickname = '$my_nick',
						profile_image = '$my_profile_link',
						lastdate = '".G5_TIME_YMDHIS."' ", true);
		if(!$is_member){
			$gpf_ix = sql_insert_id();
			$my_nick = "user_".$gpf_ix;
			sql_query(" UPDATE g5_gnupushapp_free_member 
						set nickname = '$my_nick' where gpf_ix = '{$gpf_ix}' ", true);
		}

	}

	$delete_cnt = sql_fetch(" SELECT count(*) as 'cnt' from g5_gnupushapp_free_member WHERE lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ");
	if($delete_cnt['cnt'] > 0)
	{
		sql_query("DELETE FROM g5_gnupushapp_free_member WHERE lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ", true);
	}
	
	//채팅방 참여리스트
	$row_result = sql_query(" SELECT * from g5_gnupushapp_free_member where 1 ");
	for ($i=0; $row_tmp=sql_fetch_array($row_result); $i++)
	{
		if($row_tmp['mb_id'] == $my_id) continue;
		if($row_tmp['sync'] == "Y"){
			$membercount++;
		}else{
			$anonymouscount++;
		}
		$this_array = array(
			"mb_id" => $row_tmp['mb_id'],
			"nick_name" => $row_tmp['nickname'],
			"sync" => $row_tmp['sync'] ? "Y" : "N",
			"profile" => $row_tmp['profile_image']
		);

		array_push($member_list, $this_array);
	}
}

$array = array("response" => $response, "my_id" => $my_id, "sync" => $is_member_, "my_nick" => $my_nick, "my_profile_link" => $my_profile_link, "member_list" => $member_list, "membercount" => $membercount, "anonymouscount" => $anonymouscount);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();
?>