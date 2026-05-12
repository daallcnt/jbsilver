<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$key = htmlspecialchars($_REQUEST['key']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";
$content = "";
$mb_nick = "";
$time_input = "";
$recentDate = "";

if($str_mp == $masterpassword)
{
	$row_tmp = sql_fetch(" select * from g5_gnupushapp_chatroom_content where gpcc_key = '{$key}' order by gpcc_lastdate desc limit 1 ");

	if($row_tmp)
	{
		$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $row_tmp['gpcc_content']);
		$mb_nick = $row_tmp['gpcc_nick'];
		$time = $row_tmp['gpcc_lastdate'];
		$recentDate = date("Y-m-d", strtotime($time));
		$time_h = date("H",time($time));
		$time_m = date("i",time($time));
		if($time_h > 12)
		{
			$time_h = $time_h - 12;
			$time_input = "오후 " . $time_h . ":" . $time_m;
		}
		else
		{
			$time_input = "오전 " . $time_h . ":" . $time_m;
		}

		$response = "ok";

	}

	sql_query("DELETE FROM g5_gnupushapp_chatroom_content WHERE gpcc_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -3 day)  ", true);

}

$array = array("response" => $response, "mb_nick" => $mb_nick, "time" => $time_input, "content" => $content, "recentDate" => $recentDate);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>