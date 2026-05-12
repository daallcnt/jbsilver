<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$cc_ix = htmlspecialchars($_REQUEST['cc_ix']);
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
$use_profile = "false";
$profile_link = "none";
$content = "";
$nickname = "";
$time = "";
$recentDate = "";

if($str_mp == $masterpassword)
{
    $device_in = get_device_info_by_regid($_SESSION['reg_id']);
    $row_result_a = sql_fetch(" SELECT * from g5_gnupushapp_free_chatting_content where cc_ix = '$cc_ix' ");
    
    $content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $row_result_a['content']);
    if($device_in['gpr_sort'] == "A"){
        $content = str_replace('#8@plus#8@', '+', $content);
    }
    if($device_in['gpr_sort'] == "I"){
        $content = str_replace('+', '#8@plus#8@', $content);
    }

    $nickname = $row_result_a['nickname'];

    if($row_result_a['profile_image'] != "none"){
        $use_profile = "true";
        $profile_link = $row_result_a['profile_image'];
    }

    $time_h = date("H",strtotime($row_result_a['regdate']));
    $time_m = date("i",strtotime($row_result_a['regdate']));
    if($time_h > 12)
    {
        $time_h = $time_h - 12;
        $time_input = "오후 " . $time_h . ":" . $time_m;
    }
    else
    {
        $time_input = "오전 " . $time_h . ":" . $time_m;
    }
    
    $time = $time_input;
    $recentDate = date("Y-m-d", strtotime( $row_result_a['regdate'] ) );

    $response = "ok";
}

$array = array("response" => $response, "mb_nick" => $nickname, "time" => $time, "content" => $content, "profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>