<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$nowDate = htmlspecialchars($_REQUEST['nowDate']);
$room_id = htmlspecialchars($_REQUEST['room_id']);
$room_type = htmlspecialchars($_REQUEST['room_type']);
$target_mb_id = htmlspecialchars($_REQUEST['target_mb_id']);
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
$count = 0;
$target_reg_id = "";
$target_nick_name = "";
$target_profile = "false";
$target_profile_link = "none";
$push = false;
$file_origin_link = "";
$is_member_ = "N";
$my_id = "";
// type_c 3가지 처리 종류...
// 채팅푸시알림 클릭이나, 채팅목록창에서 클릭시에는 room_id와 room_type으로 처리.. target_mb_id = 빈값 ------- type_c = "room_id"
// 검색후 회원목록에서 클릭시에는 target_mb_id값으로 처리... room_id = none, room_type = onetoone ------- type_c = "target_mb_id"
// 관리자 문의는 room_type으로 처리 -> room_id = none, target_mb_id = 빈값 ------- type_c = "inquire"
// room_id,target_mb_id,inquire 
$type_c = "room_id";

if($str_mp == $masterpassword && $_SESSION['reg_id'])
{

	$device_in = get_device_info_by_regid($_SESSION['reg_id']);
	
	//비회원일 경우 임의로 만든 아이디를 부여하기 위해 미리 준비함.
	$temp_my_id = substr($_SESSION['reg_id'], 30, 18);

	if($is_member){
		$is_member_ = "Y";
		$my_id = $_SESSION['ss_mb_id'];
		

		//이전에 비회원상태에서 관리자 혹은 다른 회원에게 문의했던 채팅 db 현재 mb_id로 다 바꿔줘야 함.
		change_sync_chatting($temp_my_id,$my_id);
		
	}else{
		if($device_in['gpr_sync'] == "Y"){
			$response = "login";
			$array = array("response" => $response, "target_profile" => $target_profile, "target_profile_link" => $target_profile_link);

			$json = "";

			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();
		}else{
			if($gnu_config['chatting_admin'] != "D" && $gnu_config['chatting_nonmembers'] != "Y"){
				$response = "fail";
				$array = array("response" => $response, "target_profile" => $target_profile, "target_profile_link" => $target_profile_link);
	
				$json = "";
	
				$json = json_encode($array);
	
				header('Content-Type: application/json; charset=utf-8');
				header('Content-Length: ' . mb_strlen($json));
				echo $json;
				exit();
			}
			$my_id = $temp_my_id;
		}
	}

	if($room_id == "none"){
		$type_c = "target_mb_id";
	}else{
		$room_result = sql_query(" select * from g5_gnupushapp_newchatting_room_joinlist where cr_ix = '$room_id' ");
		for ($i=0; $row_tmp=sql_fetch_array($room_result); $i++)
		{
			if($row_tmp['reg_id'] != $_SESSION['reg_id'] && $row_tmp['mb_id'] != $my_id){
				$target_mb_id = $row_tmp['mb_id'];
			}
		}
	}

	if($room_type == "inquire"){
		$type_c = "inquire";
		$target_mb_id = $gnu_config['chatting_admin_id'];
	}	

	$target_member_info = get_member($target_mb_id);
	
	if(!$target_member_info['mb_id']){
		if(!$is_member || ($gnu_config['chatting_admin_id'] != $my_id && $gnu_config['chatting_nonmembers'] == "N")){
			$response = "fail";
			$array = array("response" => $response, "target_profile" => $target_profile, "target_profile_link" => $target_profile_link);
	
			$json = "";
	
			$json = json_encode($array);
	
			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();
		}else{
			$target_nick_name = "user" . substr($target_mb_id, 8, 6);
		}
		
	}else{

		$target_nick_name = $target_member_info['mb_nick'];

		$mb_icon_url = get_gnu_profile_image($target_mb_id);
		if($mb_icon_url){
			$target_profile = "true";
			$target_profile_link = $mb_icon_url;
		}
	}

	$exist = false;
	$cr_ix = 0;
	$join_date = "";

	if($type_c == "room_id"){
		//현재 방번호 안에 내가 포함되어 있는지 검사..
		$check_result = sql_fetch(" select count(*) as cnt from g5_gnupushapp_newchatting_room_joinlist where mb_id = '$my_id' and cr_ix = '$room_id' and (c_status = 'join' or c_status = 'nowjoin') ");
		if($check_result['cnt'] > 0){
			$exist = true;
			$cr_ix = $room_id;
		}
	}else{
		$row_result = sql_fetch(" select a.cr_ix,a.c_status from g5_gnupushapp_newchatting_room_joinlist as a join g5_gnupushapp_newchatting_room_joinlist as b where a.cr_ix = b.cr_ix and a.mb_id = '$my_id' and b.mb_id = '$target_mb_id' ");

		if($row_result['cr_ix']){
			if($row_result['c_status'] == "ban") {
				$response = "fail";
				$array = array("response" => $response, "target_profile" => $target_profile, "target_profile_link" => $target_profile_link);

				$json = "";

				$json = json_encode($array);

				header('Content-Type: application/json; charset=utf-8');
				header('Content-Length: ' . mb_strlen($json));
				echo $json;
				exit();
			}else{
				$exist = true;
				$cr_ix = $row_result['cr_ix'];
			}
		}
	}

	// 현재 나의 상태를 nowjoin으로 바꿀 것.
	sql_query(" update g5_gnupushapp_newchatting_room_joinlist set c_status = 'join', join_date = '".G5_TIME_YMDHIS."' where mb_id = '$my_id' and cr_ix = '$cr_ix' ");

	$join_result = sql_fetch(" select regdate from g5_gnupushapp_newchatting_room_joinlist where mb_id = '$my_id' and cr_ix = '$cr_ix' ");

	if($exist){
		//join한 이후의 데이터만 가져와야 함. out 한 후 다시 join했는데, 기존 자료가 남아있으면 안됨.
		$row_result_count = sql_fetch(" select count(*) as cnt from g5_gnupushapp_newchatting_content where cr_ix = '$cr_ix' and c_status = 'ok' and regdate > '{$join_result['regdate']}' ");
		if($row_result_count['cnt'] > 0){

			//이 방에 내가 읽지 않은 목록이 있을 경우 상대방에게 읽음이라고 푸시처리해야 함.
			$read_result_count = sql_fetch("select count(*) as cnt from g5_gnupushapp_newchatting_content_readlist where cr_ix = '$cr_ix' and mb_id = '$my_id' and is_readed = 'N'");


			if($read_result_count['cnt'] > 0){
				$push = true;
				sql_query("update g5_gnupushapp_newchatting_content_readlist set is_readed = 'Y', read_date = '".G5_TIME_YMDHIS."' where cr_ix = '$cr_ix' and mb_id = '$my_id' and is_readed = 'N'");

			}
			//해당 방에서 안읽음이 없을 경우 
			$read_result_total_count = sql_fetch("select count(*) as cnt from g5_gnupushapp_newchatting_content_readlist where cr_ix = '$cr_ix' and is_readed = 'N'");
			if($read_result_total_count['cnt'] == 0){
				sql_query("update g5_gnupushapp_newchatting_content set is_readed = 'Y' where cr_ix = '$cr_ix' ");
			}

			if($nowDate != "0000-00-00")
			{
				$benchmark = date('Y-m-d', strtotime($nowDate));
				$row_result = sql_query(" select * from g5_gnupushapp_newchatting_content where cr_ix = '$cr_ix' and regdate < $benchmark and c_status = 'ok' and regdate > '{$join_result['regdate']}' order by regdate desc ");
			}else{
				$row_result = sql_query(" select * from g5_gnupushapp_newchatting_content where cr_ix = '$cr_ix' and c_status = 'ok' and regdate > '{$join_result['regdate']}' order by regdate desc ");
			}

			$array = array("response" => "ok", "target_profile" => $target_profile, "target_profile_link" => $target_profile_link, "room_id" => strval($cr_ix), "target_mb_id" => $target_mb_id, "target_nick_name" => $target_nick_name);
			$array_pre = array();
			for ($i=0; $row_tmp=sql_fetch_array($row_result); $i++)
			{
				$regdate_new = date("Y-m-d", strtotime($row_tmp['regdate']));
				$process_ok = false;

				if($count > 20)
				{
					if($regdate == $regdate_new)
					{
						$process_ok = true;
					}
				}
				else
				{
					$process_ok = true;
				}

				if($process_ok)
				{
					$is_readed = $row_tmp['is_readed'];

					$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $row_tmp['content']);
					if($device_in['gpr_sort'] == "I")
					{
						$content = str_replace('+', '#8@plus#8@', $content);
					}
					else
					{
						$content = str_replace('#8@plus#8@', '+', $content);
					}

					$regdate = date('Y-m-d', strtotime($row_tmp['regdate']));

					$time_h = date("H",strtotime($row_tmp['regdate']));
					$time_m = date("i",strtotime($row_tmp['regdate']));
					if($time_h > 12)
					{
						$time_h = $time_h - 12;
						$time = "오후 " . $time_h . ":" . $time_m;
					}
					else
					{
						$time = "오전 " . $time_h . ":" . $time_m;
					}

					$is_file = $row_tmp['is_file'];
					$is_image = $row_tmp['is_image'];

					$file_link = "";

					if($is_file == "Y")
					{
						$fileDate = date("YmdHms",strtotime($row_tmp['regdate']));

						$filename = $fileDate . $row_resrow_tmpult_a['content'];
						
						$file_link = G5_DATA_URL.'/gnupushchat/'.$row_tmp['cc_ix'] ."/". $row_tmp['filepath'];
						$file_origin_link = G5_DATA_URL.'/gnupushchat/'.$row_tmp['cc_ix'] ."/". $row_tmp['filepath'];

						if($is_image == "Y")
						{
							$file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_tmp['cc_ix'];
							$mb_image_path = $file_dir . '/'  . $row_tmp['filepath'];
							if(file_exists($mb_image_path)){
								$size = @getimagesize($mb_image_path);

								$target_path = $file_dir = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$row_tmp['cc_ix'];

								if(!is_dir($target_path)) {
									@mkdir($target_path, G5_DIR_PERMISSION,true);
									@chmod($target_path, G5_DIR_PERMISSION,true);
								}

								$newfile = $target_path. '/' . $filename;

								if (copy($mb_image_path, $newfile)) {

									$thumb_filename = basename($newfile);
									$filepath = dirname($newfile);

									if($size[0] > 500)
									{
										$size_y = round(( $size[1] * 450 ) / $size[0]);
										$thumb = thumbnail($thumb_filename, $filepath, $filepath, 450, $size_y, false, false, 'center', false, $um_value='80/0.5/3');
									}
									else
									{
										$thumb = thumbnail($thumb_filename, $filepath, $filepath, $size[0], $size[1], false, false, 'center', false, $um_value='80/0.5/3');
									}

									if(file_exists($newfile)){
										@unlink($newfile);
									}

									$file_link = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
								}
							}
						}

					}

					$thisarray = array(
						"key" => $row_tmp['cc_ix'],
						"send_mb_id" => $row_tmp['mb_id'],
						"content" => $content,
						"send_time" => $time,
						"is_readed" => $is_readed,
						"date" => $regdate,
						"is_file" => $is_file,
						"is_image" => $is_image,
						"file_link" => $file_link,
						"file_origin_link" => $file_origin_link
					);

					$arrayval = urlencode(json_encode($thisarray));

					array_push($array_pre, $arrayval);

					
				}else{
					break;
				}
				$count++;

			}


			if($push)
			{

				$to_reg_id = false;

				if(!$target_member_info['mb_id'] && $gnu_config['chatting_admin_id'] == $my_id){
					$to_reg_id = true;
					$join_result = sql_fetch(" select regdate from g5_gnupushapp_newchatting_room_joinlist where mb_id <> '$my_id' and cr_ix = '$cr_ix' and (c_status = 'join' or c_status = 'nowjoin') ");
					$target_reg_id = $join_result['reg_id'];
				}

				//푸시알림 발송
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
				$etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $cr_ix;
				if($to_reg_id){
					$reg_ids = array($target_reg_id);
					quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "read");
				}else{
					$member_ids = array($target_mb_id);
					quick_send($member_ids, $title, $content, $address, $etc, $sort, false, "read");
				}

			}

			$array["count"] = $count;

			$array_reverse = array_reverse($array_pre);

			for($i=0;$i<$count;$i++)
			{
				$array["chat".$i] = $array_reverse[$i];
			}

			$json = "";

			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();
		}else{
			$array = array("response" => "none", "target_profile" => $target_profile, "target_profile_link" => $target_profile_link, "room_id" => strval($cr_ix), "target_mb_id" => $target_mb_id, "target_nick_name" => $target_nick_name);
			$json = "";

			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();
		}

	}else{

		if($room_type == "inquire"){
			$name = "1:1문의";
		}else{
			$name = "1:1채팅";
		}

		$join_list = $my_id . "," . $target_mb_id;

		//방생성하기
		sql_query(" INSERT INTO g5_gnupushapp_newchatting_room 
				set type = '{$room_type}',
				name = '{$name}',
				member_num = 2,
				secret = 'Y',
				join_list = '$join_list',
				maker_is_member = '{$is_member_}',
				maker_mb_id = '{$my_id}',
				maker_reg_id = '{$_SESSION['reg_id']}',
				c_status = 'ok',
				regdate = '".G5_TIME_YMDHIS."',
				up_date = '".G5_TIME_YMDHIS."'
				", true);

		$last_uid = sql_insert_id();

		//방참여 db에 두 당사자 정보 넣기.
		sql_query(" INSERT INTO g5_gnupushapp_newchatting_room_joinlist 
				set cr_ix = '{$last_uid}',
				is_member = '{$is_member_}',
				mb_id = '{$my_id}',
				reg_id = '{$_SESSION['reg_id']}',
				c_status = 'join',
				join_date = '".G5_TIME_YMDHIS."',
				out_date = '".G5_TIME_YMDHIS."',
				regdate = '".G5_TIME_YMDHIS."'
				", true);


		sql_query(" INSERT INTO g5_gnupushapp_newchatting_room_joinlist 
				set cr_ix = '{$last_uid}',
				is_member = 'Y',
				mb_id = '{$target_mb_id}',
				reg_id = 'none',
				c_status = 'join',
				join_date = '".G5_TIME_YMDHIS."',
				out_date = '".G5_TIME_YMDHIS."',
				regdate = '".G5_TIME_YMDHIS."'
				", true);

		$array = array("response" => "none", "target_profile" => $target_profile, "target_profile_link" => $target_profile_link, "room_id" => strval($last_uid), "target_mb_id" => $target_mb_id, "target_nick_name" => $target_nick_name);
		$json = "";

		$json = json_encode($array);

		header('Content-Type: application/json; charset=utf-8');
		header('Content-Length: ' . mb_strlen($json));
		echo $json;
		exit();
	}



}



?>