<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$sync = htmlspecialchars($_REQUEST['sync']);
$mb_nick = htmlspecialchars($_REQUEST['mb_nick']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";
$nick_json = "";
$count = 0;
$new_entrance = "false";
$new_out = false;
$new_out_json = "none";
$out_count = 0;
$new_nick = "user";

if($str_mp == $masterpassword)
{

	$delete_cnt = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom WHERE gpcr_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ");
	if($delete_cnt['cnt'])
	{
		$new_out = true;
		$row_result = sql_query(" select * from g5_gnupushapp_chatroom WHERE gpcr_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ");
		$nick_array_out = array();
		for ($i=0; $row=sql_fetch_array($row_result); $i++)
		{
			$strcount = $out_count;
			$nick_array_out["nick".$strcount] = $row['gpcr_nick'];
			$out_count++;
		}

		$new_out_json = urlencode(json_encode($nick_array_out));

		sql_query("DELETE FROM g5_gnupushapp_chatroom WHERE gpcr_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ", true);
	}

	$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom where gpcr_reg_id = '{$reg_id}' ");

	if($row_tmp['cnt'] == 0)
	{
		if($sync == "false")
		{
			$numeric = '0123456789';
			$token = "";
			for($i = 0; $i < 6; $i++)
			{
				$token .= $numeric[mt_rand(0, strlen($numeric) - 1)];
			}
			$new_nick = "user" . $token;
			$mb_nick = $new_nick;
		}
		sql_query(" INSERT INTO g5_gnupushapp_chatroom 
					set gpcr_reg_id = '{$reg_id}', 
					gpcr_nick = '{$mb_nick}',
					gpcr_lastdate = '".G5_TIME_YMDHIS."' ", true);
		$new_entrance = "true";
	}else{
		$sql = " update g5_gnupushapp_chatroom set gpcr_lastdate = '".G5_TIME_YMDHIS."' where gpcr_reg_id = '{$reg_id}' ";
		sql_query($sql);
	}

	$row_result = sql_query(" select * from g5_gnupushapp_chatroom ");
	$reg_ids = array();
	for ($i=0; $row=sql_fetch_array($row_result); $i++)
	{
		if($reg_id != $row['gpcr_reg_id'])
		{
			array_push($reg_ids, $row['gpcr_reg_id']);
		}

		$count++;
	}
	if($count > 0)
	{
		$response = "ok";
	}
	else
	{
		$response = "none";
	}

	if($new_entrance == "true" || $new_out)
	{
		//입장과 퇴장을 일괄푸시알림
		$use_profile = "false";
		$profile_link = "none";
		$pushstyle = "big_text";
		$image_src = "none";
		$title = "(전체채팅) " . $mb_nick . "님";
		$ticker = $title;
		$content = "none";
		$address = G5_URL;
		$bottom_text = G5_URL;
		$type = "sms";
		$banner = "headsup";
		$sort = "must";
		$etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $new_entrance . "ab&#ba" . $mb_nick . "ab&#ba" . $out_count . "ab&#ba" . $new_out_json;
		quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "chatroom_new");

	}

}

$array = array("response" => $response, "count" => $count, "out_count" => $out_count, "new_out_json" => $new_out_json, "new_nick" => $new_nick);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();



?>