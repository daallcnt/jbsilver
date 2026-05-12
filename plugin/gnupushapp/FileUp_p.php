<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);
$r_num = htmlspecialchars($_REQUEST['r_num']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($session_r_num = get_session('gnupushapp_file_up')) {
	if($r_num == $session_r_num && $str_mp == $masterpassword)
	{
		$error = false;

		$file_info = $_FILES['Filedata'];
		if(preg_match('/^=\?UTF-8\?B\?(.+)\?=$/i', $file_info['name'], $match))
		{
			$file_name_temp = base64_decode(strtr($match[1], ':', '/'));
		}
		$tmp_file  = $file_info['tmp_name'];

		if($gnu_config['build_sort'] == 'A')
		{

			// 회원 아이콘
			$temp_dir = G5_DATA_PATH.'/apms/photo/temp';
			$mb_dir = G5_DATA_PATH.'/member/gnupushpf';
			$mb_icon_path = G5_DATA_PATH.'/member/gnupushpf/'.$r_num.'.jpg';
			if(file_exists($mb_icon_path)){
				@unlink($mb_icon_path);
			}

			if(!is_dir($mb_dir)) {
				@mkdir($mb_dir, G5_DIR_PERMISSION);
				@chmod($mb_dir, G5_DIR_PERMISSION);
			}

			if(!is_dir($temp_dir)) {
				@mkdir($temp_dir, G5_DIR_PERMISSION);
				@chmod($temp_dir, G5_DIR_PERMISSION);
			}


			//Photo Size
			$photo_w = (isset($xp['xp_photo']) && $xp['xp_photo']) ? $xp['xp_photo'] : 80;
			$photo_h = $photo_w;

			if (is_uploaded_file($file_info['tmp_name'])) {
				if (!preg_match("/(\.(jpg|jpeg|gif|png))$/i", $file_name_temp)) {
					$error = true;
					$error_m = $file_name_temp.'은(는) 이미지(gif/jpg/png) 파일이 아닙니다.';
				} else {
					$filename  = $file_name_temp;
					$filename  = preg_replace('/(<|>|=)/', '', $filename);
					$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

					$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
					shuffle($chars_array);
					$shuffle = implode('', $chars_array);
					$filename = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

					$org_photo = $mb_icon_path;
					$temp_photo = $temp_dir."/".$filename;

					move_uploaded_file($tmp_file, $temp_photo) or die($file_info['error']);
					chmod($temp_photo, G5_FILE_PERMISSION);
					if(is_file($temp_photo)) {
						$size = @getimagesize($temp_photo);

						//Non Image
						if (!$size[0]) {
							@unlink($temp_photo);
							$error = true;
							$error_m = '회원사진 등록에 실패했습니다. 이미지 파일이 정상적으로 업로드 되지 않았거나, 이미지 파일이 아닙니다.';
						}			

						//Animated GIF
						$is_animated = false;
						if($size[2] == 1) {
							$is_animated = is_animated_gif($temp_photo);
						}

						if($is_animated) {
							@unlink($temp_photo);
							$error = true;
							$error_m = '움직이는 GIF 파일은 회원사진으로 등록할 수 없습니다.';
						} else {
							$thumb = thumbnail($filename, $temp_dir, $temp_dir, $photo_w, $photo_h, true, true);
							if($thumb) {
								copy($temp_dir.'/'.$thumb, $org_photo);
								chmod($org_photo, G5_FILE_PERMISSION);
								@unlink($temp_dir.'/'.$thumb);
								@unlink($temp_photo);
								sql_query(" update {$g5['member_table']} set as_photo = '1' where mb_id = '$mb_id' ", false);
								$image_src  = G5_DATA_URL.'/member/gnupushpf/'.$r_num.'.jpg';
								set_session('gnupushapp_file_img_src', $image_src);
							} else {
								@unlink($temp_photo);
								$error = true;
								$error_m = '회원사진 등록에 실패했습니다. 이미지 파일이 정상적으로 업로드 되지 않았거나, 이미지 파일이 아닙니다.';
							}
						}
					}
				}
			}

		}else{

			
			// 회원 아이콘
			$mb_dir = G5_DATA_PATH.'/member/gnupushpf';
			$mb_icon_path = G5_DATA_PATH.'/member/gnupushpf/'.$r_num.'.gif';
			if(file_exists($mb_icon_path)){
				@unlink($mb_icon_path);
			}

			if(!is_dir($mb_dir)) {
				@mkdir($mb_dir, G5_DIR_PERMISSION);
				@chmod($mb_dir, G5_DIR_PERMISSION);
			}

			if (is_uploaded_file($file_info['tmp_name'])) {
				if (preg_match("/(\.gif)$/i", $file_name_temp)) {
					// 아이콘 용량이 설정값보다 이하만 업로드 가능
					if ($file_info['size'] <= $config['cf_member_icon_size']) {
						$dest_path = $mb_dir.'/'.$r_num.'.gif';
						move_uploaded_file($tmp_file, $dest_path);
						chmod($dest_path, G5_FILE_PERMISSION);
						if (file_exists($dest_path)) {
							//=================================================================\
							// 090714
							// gif 파일에 악성코드를 심어 업로드 하는 경우를 방지
							// 에러메세지는 출력하지 않는다.
							//-----------------------------------------------------------------
							$size = getimagesize($dest_path);
							if ($size[2] != 1) // gif 파일이 아니면 올라간 이미지를 삭제한다.
								@unlink($dest_path);
							else
							// 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
							if ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']){
								@unlink($dest_path);
								$error = true;
								$error_m = '회원아이콘 이미지 크기가 '.$config['cf_member_icon_width'].'px X '.$config['cf_member_icon_height'].'px 를 넘으면 안 됩니다.';
							}
							//=================================================================\
							$image_src  = G5_DATA_URL.'/member/gnupushpf/'.$r_num.'.gif';
							set_session('gnupushapp_file_img_src', $image_src);
						}
					} else {
						$error = true;
						$error_m = '회원아이콘을 '.number_format($config['cf_member_icon_size']).'바이트 이하로 업로드 해주십시오.';
					}

				} else {
					$error = true;
					$error_m = $file_name_temp . '은(는) gif 파일이 아닙니다.';
				}
			}

		}

		if($error){

			$array = array("data" => $error_m);			

		}else{

			$array = array("data" => "ok");

		}

		$json = "";

		$json = json_encode($array);

		header('Content-Type: application/json; charset=utf-8');
		header('Content-Length: ' . mb_strlen($json));
		echo $json;

	}
}

exit();

?>