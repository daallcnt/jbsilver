<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$mb_id = htmlspecialchars($_REQUEST['mb_id']);
$my_id = htmlspecialchars($_REQUEST['my_id']);
$issync = htmlspecialchars($_REQUEST['is_sync']);
$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$value = htmlspecialchars($_REQUEST['value']);
$key = htmlspecialchars($_REQUEST['key']);
$random = htmlspecialchars($_REQUEST['random']);
$is_file = htmlspecialchars($_REQUEST['is_file']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";
$image_src = "none";
$delete_cnt = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios WHERE gpcr_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ");
if($delete_cnt['cnt'] > 0)
{
	sql_query("DELETE FROM g5_gnupushapp_chatroom_ios WHERE gpcr_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ", true);
}

//채팅방개설 설정 체크 & mb_id amdin인지 체크

$to_reg_id = false; // 관리자가 비회원에 답변할 경우에만 true
$reg_id_anonymous = "none"; // 관리자가 비회원에 답변할 경우에만 reg_id값 아닐 경우 none
$gpc_anonymous = "N"; // 비회원이 관리자에게 문의한 경우에만 Y
$last_uid = "pushthumb";

if($str_mp == $masterpassword && $reg_id && $is_file == "Y" && $gnu_config['chatting_file'] != 'N')
{
	$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$reg_id}' ");

	if($row_tmp['cnt'] != 0)
	{
		$row_tmp2 = sql_fetch(" select * from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$reg_id}' ");
		$random_origin = $row_tmp2['gpci_random'];

		if($random == $random_origin)
		{

			if($issync == "Y"){
				$my_member_info = get_member($my_id);
				$nick_name = $my_member_info['mb_nick'];
			}else{

				if($gnu_config['chatting_admin_id'] == $mb_id && $gnu_config['chatting_admin'] == "D"){
					$gpc_anonymous = "Y";
					$my_id = substr($reg_id, 30, 18);
					$nick1 = substr($reg_id, 50, 8);
					$nick_name = "user" . $nick1;
					$my_device = 1;
					$reg_id_anonymous = $reg_id;
				}else{

					$response = "fail";
					$array = array("response" => $response);
					$json = "";
					$json = json_encode($array);
					header('Content-Type: application/json; charset=utf-8');
					header('Content-Length: ' . mb_strlen($json));
					echo $json;
					exit();
				}


			}



		}


	}
	
	if($issync == "Y") $my_device = get_device_count_by_member_id($my_id);
	$your_device = get_device_count_by_member_id($mb_id);
	if($gnu_config['chatting_admin_id'] == $my_id && $gnu_config['chatting_admin'] == "D" && $your_device == 0){
		$your_device = 1;
	}
	if($my_device > 0 && $your_device > 0)
	{

		$error = false;

		$file_name_random = "none";

		$is_image = "N";

		// filename 가져오기
		if($gnu_config['chatting_file'] != 'N')
		{
			$file_info = $_FILES['Filedata'];
			$file_name_temp = urldecode($file_info['name']);
			$value = get_safe_filename($file_name_temp);

			$tmp_file  = $file_info['tmp_name'];
			$filesize  = $file_info['size'];
			$filename  = $file_name_temp;

			if ($filename) {
				if ($file_info['error'] == 1) {
					$error = true;
					$error_m = "파일업로드에 실패하였습니다.";
				}
				else if ($file_info['error'] != 0) {
					$error = true;
					$error_m = "파일업로드에 실패하였습니다.";
				}
			}

			if (is_uploaded_file($tmp_file)) {
				
				if ($filesize > 10485760 )
				{
					$error = true;
					$error_m = "허용된 용량을 초과하였습니다(10M 이하만 가능).";
				}

				if(preg_match("/(\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc))$/i", $file_name_temp))
				{
					$error = true;
					$error_m = "금지된 파일형식입니다.";
				}
				
				if (preg_match("/(\.(jpg|jpeg|gif|png))$/i", $file_name_temp))
				{
					$is_image = "Y";
				}

				if (!preg_match("/(\.(jpg|jpeg|gif|png))$/i", $file_name_temp))
				{
					if($gnu_config['chatting_file'] != 'A')
					{
						$error = true;
						$error_m = "이미지만 업로드하실 수 있습니다.";
					}
				}
			}

			$ext = substr($filename, -4);
			if(strtolower($ext) == 'jpeg') $ext = '.'.$ext;

			$file_random_name = get_random_string_gnu(30);

			$file_name_random = $file_random_name . $ext;
		}
		else
		{
			$error = true;
			$error_m = "파일 업로드가 불가합니다.";
		}

		

		if($error)
		{
			$response = "error";
			$array = array("response" => $response, "error_m" => $error_m);

			$json = "";

			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();

		}
		else
		{

			
			$time_h = date("H",time());
			$time_m = date("i",time());
			if($time_h > 12)
			{
				$time_h = $time_h - 12;
				$time_input = "오후 " . $time_h . ":" . $time_m;
			}
			else
			{
				$time_input = "오전 " . $time_h . ":" . $time_m;
			}
			
			$content = $value;

			$recentDate = date("Y-m-d", time());

			$row_cp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') ");

			if($row_cp['cnt'] > 0)
			{
				$row_tmp = sql_fetch(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') order by gpc_regdate desc limit 1 ");

				$nowDate = date('Y-m-d',time());
				$recentDate = date("Y-m-d", strtotime( $row_tmp['gpc_regdate'] ) );

				if($row_tmp['gpc_anonymous'] == "Y" && $gnu_config['chatting_admin_id'] == $my_id){
					$to_reg_id = true;
					$reg_id_anonymous = $row_tmp['gpc_reg_id'];
					$gpc_anonymous = "Y";
				}

				if($nowDate != $recentDate)
				{
					$arrray_text = array("mb_id" => $my_id, "mb_nick" => $nick_name, "time" => $time_input, "content" => $content, "read" => "N", "recentDate" => $recentDate, "is_file" => $is_file, "file_name" => $file_name_random, "is_image" => $is_image, "reg_date" => G5_TIME_YMDHIS );

					$chat = array( $key => $arrray_text );
					$data = base64_encode(serialize($chat));

					sql_query(" INSERT INTO g5_gnupushapp_chat 
						set gpc_mb_id1 = '{$my_id}',
						gpc_mb_id2 = '{$mb_id}',
						gpc_chat = '{$data}',
						gpc_notread = 'Y',
						gpc_reg_id = '{$reg_id_anonymous}',
						gpc_anonymous = '{$gpc_anonymous}',
						gpc_lastdate = '".G5_TIME_YMDHIS."',
						gpc_regdate = '".G5_TIME_YMDHIS."'
						", true);

					$last_uid = sql_insert_id();
					$file_dir = G5_DATA_PATH.'/gnupushchat/'.$last_uid;
					$mb_image_path = $file_dir . '/'  . $file_name_random;
					if(file_exists($mb_image_path)){
						@unlink($mb_image_path);
					}

					if(!is_dir($file_dir)) {
						@mkdir($file_dir, G5_DIR_PERMISSION);
						@chmod($file_dir, G5_DIR_PERMISSION);
					}
					move_uploaded_file($tmp_file, $mb_image_path);
					chmod($mb_image_path, G5_FILE_PERMISSION);

					$fileDate = date("YmdHms",time());

					$filename = $fileDate . $content;

					if(file_exists($mb_image_path)){
						$size = @getimagesize($mb_image_path);

						$target_path = $file_dir = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$last_uid;

						if(!is_dir($target_path)) {
							@mkdir($target_path, G5_DIR_PERMISSION);
							@chmod($target_path, G5_DIR_PERMISSION);
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

							$image_src = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
						}
					}

				}
				else
				{
					$last_uid = $row_tmp['gpc_ix'];

					$file_dir = G5_DATA_PATH.'/gnupushchat/'.$last_uid;
					$mb_image_path = $file_dir . '/' . $file_name_random;
					if(file_exists($mb_image_path)){
						@unlink($mb_image_path);
					}

					if(!is_dir($file_dir)) {
						@mkdir($file_dir, G5_DIR_PERMISSION);
						@chmod($file_dir, G5_DIR_PERMISSION);
					}
					move_uploaded_file($tmp_file, $mb_image_path);
					chmod($mb_image_path, G5_FILE_PERMISSION);


					$fileDate = date("YmdHms",time());

					$filename = $fileDate . $content;

					if(file_exists($mb_image_path)){
						$size = @getimagesize($mb_image_path);

						$target_path = $file_dir = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$last_uid;

						if(!is_dir($target_path)) {
							@mkdir($target_path, G5_DIR_PERMISSION);
							@chmod($target_path, G5_DIR_PERMISSION);
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

							$image_src = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
						}
					}

					$arrray_text = array("mb_id" => $my_id, "mb_nick" => $nick_name, "time" => $time_input, "content" => $content, "read" => "N", "recentDate" => $recentDate, "is_file" => $is_file, "file_name" => $file_name_random, "is_image" => $is_image, "reg_date" => G5_TIME_YMDHIS );


					$chat = unserialize(base64_decode($row_tmp['gpc_chat']));
					$chat[$key] = $arrray_text;
					$data = base64_encode(serialize($chat));

					$sql = " update g5_gnupushapp_chat
						set gpc_chat = '{$data}',
						gpc_notread = 'Y',
						gpc_lastdate = '".G5_TIME_YMDHIS."'
						where gpc_ix = '{$row_tmp['gpc_ix']}' ";
					sql_query($sql);

				}

			}
			else
			{
				$arrray_text = array("mb_id" => $my_id, "mb_nick" => $nick_name, "time" => $time_input, "content" => $content, "read" => "N", "recentDate" => $recentDate, "is_file" => $is_file, "file_name" => $file_name_random, "is_image" => $is_image, "reg_date" => G5_TIME_YMDHIS );

				$chat = array( $key => $arrray_text );
				$data = base64_encode(serialize($chat));

				sql_query(" INSERT INTO g5_gnupushapp_chat 
					set gpc_mb_id1 = '{$my_id}',
					gpc_mb_id2 = '{$mb_id}',
					gpc_chat = '{$data}',
					gpc_reg_id = '{$reg_id_anonymous}',
					gpc_anonymous = '{$gpc_anonymous}',
					gpc_notread = 'Y',
					gpc_lastdate = '".G5_TIME_YMDHIS."',
					gpc_regdate = '".G5_TIME_YMDHIS."'
					", true);

				$last_uid = sql_insert_id();
				$file_dir = G5_DATA_PATH.'/gnupushchat/'.$last_uid;
				$mb_image_path = $file_dir . '/'  . $file_name_random;
				if(file_exists($mb_image_path)){
					@unlink($mb_image_path);
				}

				if(!is_dir($file_dir)) {
					@mkdir($file_dir, G5_DIR_PERMISSION);
					@chmod($file_dir, G5_DIR_PERMISSION);
				}
				move_uploaded_file($tmp_file, $mb_image_path);
				chmod($mb_image_path, G5_FILE_PERMISSION);

				$fileDate = date("YmdHms",time());

				$filename = $fileDate . $content;

				if(file_exists($mb_image_path)){
					$size = @getimagesize($mb_image_path);

					$target_path = $file_dir = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$last_uid;

					if(!is_dir($target_path)) {
						@mkdir($target_path, G5_DIR_PERMISSION);
						@chmod($target_path, G5_DIR_PERMISSION);
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

						$image_src = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
					}
				}

			}

			$use_profile = "false";
			$profile_link = "none";

			if($gpc_anonymous == "N"){
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
			$image_src_push = "none";
			
			if(file_exists($mb_image_path)){

				$fileDate = date("YmdHms",strtotime(G5_TIME_YMDHIS));

				$filename = "push" . $fileDate . $content;

				$size = @getimagesize($mb_image_path);

				$target_path = $file_dir = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$last_uid;

				if(!is_dir($target_path)) {
					@mkdir($target_path, G5_DIR_PERMISSION);
					@chmod($target_path, G5_DIR_PERMISSION);
				}

				$newfile = $target_path. '/' . $filename;

				if (copy($mb_image_path, $newfile)) {

					$thumb_filename = basename($newfile);
					$filepath = dirname($newfile);

					$width=500;
					$height=250;

					$thumb = thumbnail($thumb_filename, $filepath, $filepath, $width, $height, false, true, 'center', false, $um_value='80/0.5/3');

					if(file_exists($newfile)){
						@unlink($newfile);
					}

					$pushstyle = "big_picture";
					$image_src_push = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
				}
			}


			$title = "(1:1채팅) " . $nick_name . "님";
			$ticker = $title;
			$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;', '#8@plus#8@'), array('<', '>', '"', ' ', '&', '+'), $content);
			$content = cut_str(strip_tags($content), 200, '...');
			$address = G5_URL;
			$bottom_text = G5_URL;
			$type = "sms";
			$banner = "headsup";
			$sort = "must";
			if($to_reg_id){
				$reg_ids = array($reg_id_anonymous);
				$etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src_push . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $key . "ab&#ba" . $my_id . "ab&#ba" . $nick_name;
				quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "chat");
			}else{
				$member_ids = array($mb_id);
				$etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src_push . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $key . "ab&#ba" . $my_id . "ab&#ba" . $nick_name;
				quick_send($member_ids, $title, $content, $address, $etc, $sort, false, "chat");
			}
			
			$response = "ok";


		}
	}
	else
	{
		$response = "none";
	}

}

$array = array("response" => $response, "image_src" => $image_src);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>