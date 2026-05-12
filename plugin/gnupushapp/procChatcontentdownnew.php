<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$nowDate = htmlspecialchars($_REQUEST['nowDate']);
$mb_id = htmlspecialchars($_REQUEST['mb_id']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";
$data_array_json = "";
$use_profile = "false";
$profile_link = "none";
$recentDate = "";
$count = 0;


//sql_query(" delete from g5_gnupushapp_chat where 1 ", true);

if($str_mp == $masterpassword)
{

	$device_in = get_device_info_by_regid($_SESSION['reg_id']);

	if($is_member){
		$my_id = $_SESSION['ss_mb_id'];
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

	$row_result = sql_query(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') order by gpc_regdate desc ");

	for ($i=0; $row_tmp=sql_fetch_array($row_result); $i++)
	{
		$recentDate = date("Ymd", strtotime($row_tmp['gpc_regdate']));
		$this_date = date("Ymd", strtotime($nowDate));
		if($this_date > $recentDate)
		{
			$use_profile = "false";
			$profile_link = "none";

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

			$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));

			$recentDate = date("Y-m-d", strtotime($row_tmp['gpc_regdate']));

			$response = "ok";

			$array = array("response" => $response, "use_profile" => $use_profile, "profile_link" => $profile_link, "recentDate" => $recentDate, "my_id" => $my_id);

			foreach($data_array as $key => $val)
			{
				$strcount = $count;
				$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $val['content']);

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


			break;

		}


	}

	$array['count'] = $count;

	if($count == 0)
	{

		$array["response"] = "none";

	}

	$json = "";

	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;
	exit();



}



?>