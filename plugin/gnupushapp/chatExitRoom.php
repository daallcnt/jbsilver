<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$room_id = htmlspecialchars($_REQUEST['room_id']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

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

$response = "fail";

$delete_cnt = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios WHERE gpci_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ");
if($delete_cnt['cnt'] > 0)
{
	sql_query("DELETE FROM g5_gnupushapp_chatroom_ios WHERE gpci_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ", true);
}

if($str_mp == $masterpassword && $room_id && $_SESSION['reg_id'])
{

    $device_in = get_device_info_by_regid($_SESSION['reg_id']);

	//비회원일 경우 임의로 만든 아이디를 부여하기 위해 미리 준비함.
    $my_id = substr($_SESSION['reg_id'], 30, 18);

    if($device_in['gpr_sync'] == "Y"){
        $my_id = $device_in['gpr_mb_id'];
    }

	$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_newchatting_room_joinlist where cr_ix = '{$room_id}' and mb_id = '$my_id' and c_status <> 'out' ");

	if($row_tmp['cnt'] > 0)
	{
		sql_query(" update g5_gnupushapp_newchatting_room_joinlist 
					set c_status = 'out',
					out_date = '".G5_TIME_YMDHIS."' where cr_ix = '{$room_id}' and mb_id = '$my_id' ", true);
	}

	$response = "ok";

}

$array = array("response" => $response);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();
?>