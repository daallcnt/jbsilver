<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$mb_id = htmlspecialchars($_REQUEST['mb_id']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";
$data_array_json = "";
$use_profile = "false";
$profile_link = "none";
$recentDate = "";
$count = 0;
$image_count = 0;
$push = false;


$to_reg_id = false;
$reg_id_anonymous = "none";

if($str_mp == $masterpassword)
{
	$device_in = get_device_info_by_regid($_SESSION['reg_id']);

	$my_id = "";

	if($is_member){
		$my_id = $_SESSION['ss_mb_id'];
		if($my_id == $mb_id)
		{
			if($config['cf_admin'] == $mb_id)
			{
				$response = "admin";
			}
			else
			{
				$response = "myself";
			}

			$array = array("response" => $response, "data" => $data_array_json, "use_profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate, "my_id" => $my_id, "count" => $count, "sync" =>  $device_in['gpr_sync']);

			$json = "";

			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();

		}
		else
		{

			$my_member_info = get_member($my_id);
			$my_nick_name = $my_member_info['mb_nick'];
			$sql = " select count(*) as 'cnt' from {$g5['memo_table']}
				where (me_recv_mb_id = '$mb_id' and me_send_mb_id = '$my_id') 
				or (me_recv_mb_id = '$my_id' and me_send_mb_id = '$mb_id') ";
			$row_cp = sql_fetch($sql);

			if($row_cp['cnt'] > 0)
			{
				$array = array("response" => "ok", "use_profile" => $use_profile, "profile_link" => $profile_link, "my_id" => $my_id);

				$noread_exist = "N";

				$sql = " select * from {$g5['memo_table']}
					where (me_recv_mb_id = '$mb_id' and me_send_mb_id = '$my_id') 
					or (me_recv_mb_id = '$my_id' and me_send_mb_id = '$mb_id') order by me_id desc";
				$row = sql_query($sql);
				for ($i=0; $row_tmp=sql_fetch_array($row); $i++)
				{
					if($recentDate == '')
					{
						$recentDate = date("Y-m-d", strtotime($row_tmp['me_send_datetime']));
					}
					$recentDateThis = date("Y-m-d", strtotime($row_tmp['me_send_datetime']));

					if($recentDateThis == $recentDate)
					{

						if($mb_id == $row_tmp['me_send_mb_id'])
						{
							if(substr($row_tmp['me_read_datetime'],0,1) == 0)
							{
								//읽음 처리해야 함.
								$sql = " update {$g5['memo_table']}
											set me_read_datetime = '".G5_TIME_YMDHIS."'
											where me_id = '{$row_tmp['me_id']}' ";
								sql_query($sql);

							}
						}
						else
						{
							if(substr($row_tmp['me_read_datetime'],0,1) == 0)
							{
								$noread_exist = "Y";
							}
						}
						$strcount = $count;
						$content = $row_tmp['me_memo'];

						$image_src = "none";

						$is_image = "N";

						if($row_tmp['me_chatfile'] != 'none')
						{

							$fileDate = date("YmdHms",strtotime($row_tmp['me_send_datetime']));

							$filename = $fileDate . $row_tmp['me_chatfile'];

							if (preg_match("/(\.(jpg|jpeg|gif|png))$/i", $row_tmp['me_chatfile']))
							{
								$is_image = "Y";
							}

							if($is_image == "Y")
							{
								$file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_tmp['gpc_ix'];
								$mb_image_path = $file_dir . '/'  . $val['file_name'];
								$target_path = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$row_tmp['gpc_ix'];
								$newfile = $target_path. '/' . $filename;

								if(file_exists($mb_image_path) && !file_exists($newfile)){
									$size = @getimagesize($mb_image_path);

									if(!is_dir($target_path)) {
										@mkdir($target_path, G5_DIR_PERMISSION);
										@chmod($target_path, G5_DIR_PERMISSION);
									}

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
								$image_count = $image_count + 1;
							}

						}
						$thisarray = array(
							"key" => $key,
							"mb_id" => $val['mb_id'],
							"mb_nick" => $val['mb_nick'],
							"time" => $val['time'],
							"content" => $content,
							"read" => $val['read'],
							"date" => $recentDate,
							"is_file" => $val['is_file'],
							"is_image" => $val['is_image'],
							"image_src" => $image_src
						);

						$array["chat".$strcount] = urlencode(json_encode($thisarray));
						$count++;


					}else{
						break;
					}

				}

			}else{


			}





		}
	}else{
		if($device_in['gpr_sync'] != 'N'){
			$my_id = $device_in['gpr_mb_id'];
		}else{
			$array = array("response" => $response, "data" => $data_array_json, "use_profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate, "my_id" => $my_id, "count" => $count);

			$json = "";

			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();
		}
	}

	




		
		if($_SESSION['reg_id'] && $gnu_config['chatting_admin_id'] == $mb_id && $gnu_config['chatting_admin'] == "D"){
			$my_member_info = get_member($my_id);
			$my_nick_name = $my_member_info['mb_nick'];

			$check_my_id = substr($_SESSION['reg_id'], 30, 18);
			$row_cp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chat where (gpc_mb_id1 = '{$check_my_id}' and gpc_mb_id2 = '{$mb_id}' and gpc_anonymous = 'Y') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$check_my_id}' and gpc_anonymous = 'Y') ");

			if($row_cp['cnt'] > 0)
			{

				$row_result_a = sql_query(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$check_my_id}' and gpc_mb_id2 = '{$mb_id}' and gpc_anonymous = 'Y') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$check_my_id}' and gpc_anonymous = 'Y') ");

				for ($i=0; $row_tmp=sql_fetch_array($row_result_a); $i++)
				{

					$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));
					
					foreach($data_array as $key => $val)
					{
						if($check_my_id == $val['mb_id']){
							$data_array[$key]['mb_nick'] = $my_nick_name;
							$data_array[$key]['mb_id'] = $my_id;
						}

					}

					$data = base64_encode(serialize($data_array));

					if($check_my_id == $row_tmp['gpc_mb_id1'])
					{
						$sql = " update g5_gnupushapp_chat
							set gpc_mb_id1 = '{$my_id}',
							gpc_chat = '{$data}',
							gpc_anonymous = 'N'
							where gpc_ix = '{$row_tmp['gpc_ix']}' ";
						sql_query($sql);
					}
					else
					{
						$sql = " update g5_gnupushapp_chat
							set gpc_mb_id2 = '{$my_id}',
							gpc_chat = '{$data}',
							gpc_anonymous = 'N'
							where gpc_ix = '{$row_tmp['gpc_ix']}' ";
						sql_query($sql);
					}
				}
			}
		}

	}else{

		if($_SESSION['reg_id'] && $gnu_config['chatting_admin_id'] == $mb_id && $gnu_config['chatting_admin'] == "D"){

			if($device_in['gpr_sync'] == "N"){
				$my_id = substr($_SESSION['reg_id'], 30, 18);
			}else{

				$response = "login";
				$array = array("response" => $response, "data" => $data_array_json, "use_profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate, "my_id" => $my_id, "count" => $count);

				$json = "";

				$json = json_encode($array);

				header('Content-Type: application/json; charset=utf-8');
				header('Content-Length: ' . mb_strlen($json));
				echo $json;
				exit();
			}

		}else{

			$response = "fail";
			$array = array("response" => $response, "data" => $data_array_json, "use_profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate, "my_id" => $my_id, "count" => $count);

			$json = "";

			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();

		}
	}

	if($my_id == $mb_id)
	{
		if($config['cf_admin'] == $mb_id)
		{
			$response = "admin";
		}
		else
		{
			$response = "myself";
		}

		$array = array("response" => $response, "data" => $data_array_json, "use_profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate, "my_id" => $my_id, "count" => $count, "sync" =>  $device_in['gpr_sync']);

		$json = "";

		$json = json_encode($array);

		header('Content-Type: application/json; charset=utf-8');
		header('Content-Length: ' . mb_strlen($json));
		echo $json;
		exit();

	}
	else
	{
		$use_profile = "false";
		$profile_link = "none";

		if($is_member){

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

		}

		$row_cp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') ");
		
		if($row_cp['cnt'] > 0)
		{

			$row_result_count = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}' and gpc_notread = 'Y') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}' and gpc_notread = 'Y') order by gpc_regdate desc ");

			$array = array("response" => "ok", "use_profile" => $use_profile, "profile_link" => $profile_link, "my_id" => $my_id);
			
			if($row_result_count['cnt'] > 1)
			{

				$row_result = sql_query(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}' and gpc_notread = 'Y') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}' and gpc_notread = 'Y') order by gpc_regdate asc ");

				for ($i=0; $row_tmp=sql_fetch_array($row_result); $i++)
				{

					if($row_tmp['gpc_anonymous'] == "Y" && $gnu_config['chatting_admin_id'] == $my_id){
						$to_reg_id = true;
						$reg_id_anonymous = $row_tmp['gpc_reg_id'];
					}


					$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));

					$recentDate = date("Y-m-d", strtotime($row_tmp['gpc_regdate']));

					$noread_exist = "N";

					foreach($data_array as $key => $val)
					{
						if($mb_id == $val['mb_id'])
						{
							if($val['read'] == "N")
							{
								$data_array[$key]['read'] = "Y";
								$push = true;
							}
						}
						else
						{
							if($val['read'] == "N")
							{
								$noread_exist = "Y";
							}

						}
						$strcount = $count;
						$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $val['content']);

						if($device_in['gpr_sort'] == "A"){
							$content = str_replace('#8@plus#8@', '+', $content);
						}
						if($device_in['gpr_sort'] == "I"){
							$content = str_replace('+', '#8@plus#8@', $content);
						}

						$image_src = "none";

						if($val['is_file'] == "Y")
						{
							$fileDate = date("YmdHms",strtotime($val['reg_date']));

							$filename = $fileDate . $val['content'];

							if($val['is_image'] == "Y")
							{
								$file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_tmp['gpc_ix'];
								$mb_image_path = $file_dir . '/'  . $val['file_name'];
								$target_path = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$row_tmp['gpc_ix'];
								$newfile = $target_path. '/' . $filename;

								if(file_exists($mb_image_path) && !file_exists($newfile)){
									$size = @getimagesize($mb_image_path);									

									if(!is_dir($target_path)) {
										@mkdir($target_path, G5_DIR_PERMISSION);
										@chmod($target_path, G5_DIR_PERMISSION);
									}									

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
								$image_count = $image_count + 1;
							}

						}
						$thisarray = array(
							"key" => $key,
							"mb_id" => $val['mb_id'],
							"mb_nick" => $val['mb_nick'],
							"time" => $val['time'],
							"content" => $content,
							"read" => $val['read'],
							"date" => $recentDate,
							"is_file" => $val['is_file'],
							"is_image" => $val['is_image'],
							"image_src" => $image_src
						);
						$array["chat".$strcount] = urlencode(json_encode($thisarray));
						$count++;
					}

					if($push)
					{
						$data = base64_encode(serialize($data_array));

						$sql = " update g5_gnupushapp_chat
							set gpc_chat = '{$data}',
							gpc_notread = '{$noread_exist}',
							gpc_lastdate = '".G5_TIME_YMDHIS."'
							where gpc_ix = '{$row_tmp['gpc_ix']}' ";
						sql_query($sql);
					}

				}



			}
			else
			{

				$row_tmp = sql_fetch(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') order by gpc_regdate desc limit 1 ");
				
				if($row_tmp['gpc_anonymous'] == "Y" && $gnu_config['chatting_admin_id'] == $my_id){
					$to_reg_id = true;
					$reg_id_anonymous = $row_tmp['gpc_reg_id'];
				}

				$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));

				$recentDate = date("Y-m-d", strtotime($row_tmp['gpc_regdate']));

				$noread_exist = "N";

				foreach($data_array as $key => $val)
				{
					if($mb_id == $val['mb_id'])
					{
						if($val['read'] == "N")
						{
							$data_array[$key]['read'] = "Y";
							$push = true;
						}
					}
					else
					{
						if($val['read'] == "N")
						{
							$noread_exist = "Y";
						}
					}
					$strcount = $count;
					$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $val['content']);

					if($device_in['gpr_sort'] == "A"){
						$content = str_replace('#8@plus#8@', '+', $content);
					}
					if($device_in['gpr_sort'] == "I"){
						$content = str_replace('+', '#8@plus#8@', $content);
					}

					$image_src = "none";

					if($val['is_file'] == "Y")
					{
						$fileDate = date("YmdHms",strtotime($val['reg_date']));

						$filename = $fileDate . $val['content'];

						if($val['is_image'] == "Y")
						{
							$file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_tmp['gpc_ix'];
							$mb_image_path = $file_dir . '/'  . $val['file_name'];
							$target_path = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$row_tmp['gpc_ix'];
							$newfile = $target_path. '/' . $filename;

							if(file_exists($mb_image_path) && !file_exists($newfile)){
								$size = @getimagesize($mb_image_path);

								if(!is_dir($target_path)) {
									@mkdir($target_path, G5_DIR_PERMISSION);
									@chmod($target_path, G5_DIR_PERMISSION);
								}

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
							$image_count = $image_count + 1;
						}

					}
					$thisarray = array(
						"key" => $key,
						"mb_id" => $val['mb_id'],
						"mb_nick" => $val['mb_nick'],
						"time" => $val['time'],
						"content" => $content,
						"read" => $val['read'],
						"date" => $recentDate,
						"is_file" => $val['is_file'],
						"is_image" => $val['is_image'],
						"image_src" => $image_src
					);

					$array["chat".$strcount] = urlencode(json_encode($thisarray));
					$count++;
				}
				if($push)
				{

					$data = base64_encode(serialize($data_array));

					$sql = " update g5_gnupushapp_chat
						set gpc_chat = '{$data}',
						gpc_lastdate = '".G5_TIME_YMDHIS."'
						where gpc_ix = '{$row_tmp['gpc_ix']}' ";
					sql_query($sql);
				}


			}

			if($push)
			{
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
				$etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $my_id;
				if($to_reg_id){
					$reg_ids = array($reg_id_anonymous);
					quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "read");
				}else{
					$member_ids = array($mb_id);
					quick_send($member_ids, $title, $content, $address, $etc, $sort, false, "read");
				}

			}

			$array["count"] = $count;
			$array["image_count"] = $image_count;
			$array["sync"] = $device_in['gpr_sync'];
			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();
		}
		else
		{

			$response = "none";
			$array = array("response" => $response, "data" => $data_array_json, "use_profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate, "my_id" => $my_id, "count" => $count, "sync" => $device_in['gpr_sync']);

			$json = "";

			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();

		}
	}

}
else
{

	$array = array("response" => $response, "data" => $data_array_json, "use_profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate, "my_id" => $my_id, "count" => $count);

	$json = "";

	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;
	exit();



}



?>