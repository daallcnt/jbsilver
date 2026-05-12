<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$nick_name = htmlspecialchars($_REQUEST['mb_nick']);
$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$key = htmlspecialchars($_REQUEST['key']);
$value = htmlspecialchars($_REQUEST['value']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";

if($str_mp == $masterpassword)
{

	$content = htmlspecialchars(urldecode(base64_decode($value)));
	$sql = " update g5_gnupushapp_chatroom set gpcr_lastdate = '".G5_TIME_YMDHIS."' where gpcr_reg_id = '{$reg_id}' ";
	sql_query($sql);

	sql_query(" INSERT INTO g5_gnupushapp_chatroom_content 
					set gpcc_key = '{$key}',
					gpcc_content = '{$content}',
					gpcc_nick = '{$nick_name}',
					gpcc_lastdate = '".G5_TIME_YMDHIS."'
					", true);

	$device = get_device_info_by_regid($reg_id);

	$response = "ok";
	$reg_ids = array();

	$row_result = sql_query(" select * from g5_gnupushapp_chatroom ");
	for ($i=0; $row=sql_fetch_array($row_result); $i++)
	{
		if($reg_id != $row['gpcr_reg_id']) array_push($reg_ids, $row['gpcr_reg_id']);
	}

	$use_profile = "false";
	$profile_link = "none";

	if($device['gpr_sync'] != "N" && $is_member)
	{
		$my_id = $_SESSION['ss_mb_id'];

		if($gnu_config['build_sort'] == 'A')
		{
			$mb_icon_path = G5_DATA_PATH.'/apms/photo/'.substr($my_id,0,2).'/'.$my_id.'.jpg';
			$mb_icon_url  = G5_DATA_URL.'/apms/photo/'.substr($my_id,0,2).'/'.$my_id.'.jpg';
		}else{
			$mb_icon_path = G5_DATA_PATH.'/member/'.substr($my_id,0,2).'/'.$my_id.'.gif';
			$mb_icon_url  = G5_DATA_URL.'/member/'.substr($my_id,0,2).'/'.$my_id.'.gif';
		}
		if(file_exists($mb_icon_path)){
			$use_profile = "true";
			$profile_link = $mb_icon_url;
		}
	}
	$pushstyle = "big_text";
	$image_src = "none";
	$title = "(전체채팅) " . $nick_name . "님";
	$ticker = $title;
	$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $content);
	$content = cut_str(strip_tags($content), 200, '...');
	$address = G5_URL;
	$bottom_text = G5_URL;
	$type = "sms";
	$banner = "headsup";
	$sort = "must";
	$etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $key;
	quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "chatroom");

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