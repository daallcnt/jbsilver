<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$mb_nick = htmlspecialchars($_REQUEST['mb_nick']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword)
{
	$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom where gpcr_reg_id = '{$reg_id}' ");

	if($row_tmp['cnt'] > 0)
	{
		sql_query(" DELETE FROM g5_gnupushapp_chatroom where gpcr_reg_id = '{$reg_id}' ", true);
	}

	$reg_ids = array();
	$count = 0;

	$row_result = sql_query(" select * from g5_gnupushapp_chatroom ");
	for ($i=0; $row=sql_fetch_array($row_result); $i++)
	{
		$count++;
		array_push($reg_ids, $row['gpcr_reg_id']);
	}
	if($count > 0)
	{
		//현재 접속중인 모든 사용자에게 퇴장을 알림
		$use_profile = "false";
		$profile_link = "none";
		$pushstyle = "big_text";
		$image_src = "none";
		$title = "none";
		$ticker = $title;
		$content = "none";
		$address = G5_URL;
		$bottom_text = G5_URL;
		$type = "sms";
		$banner = "headsup";
		$sort = "must";
		$etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $mb_nick;
		quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "chatroomout");

	}


}

exit();



?>