<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$array = array();
$my_id = "none";
$iamanonymous = false;

if($str_mp == $masterpassword)
{
	$device_in = get_device_info_by_regid($_SESSION['reg_id']);

	if($is_member){
		$my_id = $_SESSION['ss_mb_id'];
	}else{

		if($_SESSION['reg_id'] && $gnu_config['chatting_admin'] == "D"){

			if($device_in['gpr_sync'] == "N"){
				$my_id = substr($_SESSION['reg_id'], 30, 18);
				$iamanonymous = true;
			}

		}else{

			$array["response"] = "fail";
			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();

		}
	}

	$row_result = sql_query(" select * from g5_gnupushapp_chat where gpc_mb_id1 = '{$my_id}' or gpc_mb_id2 = '{$my_id}' order by gpc_regdate desc");

	$count = 0;
	$import_mb_id_array = array();

	for ($i=0; $row_tmp=sql_fetch_array($row_result); $i++)
	{
		$use_profile = "false";
		$profile_link = "none";

		if($row_tmp['gpc_mb_id1'] == $my_id){
			$mb_id = $row_tmp['gpc_mb_id2'];
		}else{
			$mb_id = $row_tmp['gpc_mb_id1'];
		}

		if($iamanonymous && $gnu_config['chatting_admin_id'] != $mb_id) continue;

		$anonymous = false;

		if($row_tmp['gpc_anonymous'] == "Y" && $gnu_config['chatting_admin_id'] == $my_id){
			//관리자가 접속하여 비회원과의 대화목록을 가져옴.
			$anonymous = true;
		}

		if(is_array($import_mb_id_array) && in_array($mb_id, $import_mb_id_array))
		{
			$read = 0;
			foreach($array as $key => $val)
			{
				$array_decode = json_decode($val);
				if($mb_id == $array_decode['mb_id']){
					$read = $array_decode['read'];
					$not_read = 0;
					$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));
					foreach($data_array as $key => $val)
					{
						if($val['read'] == "N" && $mb_id == $val['mb_id'])
						{
							$not_read++;
						}
					}
					if($not_read > 0)
					{
						$newread = $read + $not_read;
						$array_decode['read'] = intval($newread);
						$array[$key] = json_encode($array_decode);

					}

					break;
				}
			}

		}
		else
		{
			array_push($import_mb_id_array, $mb_id);

			if(!$anonymous){

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

				$member_info_you = get_member($mb_id);
				$nick_name = $member_info_you['mb_nick'];
			}

			$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));

			$recentDate = date("Y-m-d", strtotime( $row_tmp['gpc_regdate'] ) );

			//마지막채팅 내용 & not read 갯수 가져오기

			$not_read = 0;
			$last_content = "";

			foreach($data_array as $key => $val)
			{
				if($val['read'] == "N" && $mb_id == $val['mb_id'])
				{
					$not_read++;
				}
				$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $val['content']);
				if($device_in['gpr_sort'] == "A"){
					$content = str_replace('#8@plus#8@', '+', $content);
				}
				if($device_in['gpr_sort'] == "I"){
					$content = str_replace('+', '#8@plus#8@', $content);
				}
				$last_content = $content;
				$time_l = $val['time'];
				if($mb_id == $val['mb_id'] && $anonymous) $nick_name = $val['mb_nick'];
				
			}

			$nowDate = date('Y-m-d',time());
			if($nowDate != $recentDate)
			{
				$time_final = date("y.m.d", strtotime( $row_tmp['gpc_regdate'] ) );
			}
			else
			{
				$time_final = $time_l;
			}

			//배열json 만들기
			$strcount = $count;
			$thisarray = array("mb_id" => $mb_id, "mb_nick" => $nick_name, "time" => $time_final, "content" => $last_content, "read" => strval($not_read), "use_profile" => $use_profile, "profile_link" => $profile_link);
			$array["chatroom".$strcount] = urlencode(json_encode($thisarray));

			$count++;

		}


	}

	$array["count"] = $count;
	$array["response"] = "ok";
	$array["sync"] = $device_in['gpr_sync'];
	$array["my_id"] = $my_id;
	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;
	exit();


}
else
{
	$array["response"] = "fail";
	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;
	exit();
}



?>