<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');

$gnu_config = get_gnupushapp_config();

$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);
$r_num = htmlspecialchars($_REQUEST['r_num']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($session_r_num = get_session('gnupushapp_file_up') && $bo_table = get_session('gnupushapp_file_bo_table')) {
	
	if($r_num == $session_r_num && $str_mp == $masterpassword)
	{
		$upload['file']     = '';
		$upload['source']   = '';
		$upload['filesize'] = 0;
		$upload['image']    = array();
		$upload['image'][0] = '';
		$upload['image'][1] = '';
		$upload['image'][2] = '';
		$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
		$error = false;
		// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
		@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
		@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);

		$board_S = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table' ");

		$bf_no_array = array();
		$file_count = 0;

		$query = "select bf_no,bf_file from {$g5['board_file_table']} where bf_rstring = '{$r_num}'";
		$wr_p = sql_query($query);
		for ($i=0; $row_p=sql_fetch_array($wr_p); $i++)
		{
			if($row_p['bf_file']){
				$file_count++;
				array_push($bf_no_array, $row_p['bf_no']);
			}
		}

		$new_bf_no = 0;

		if($file_count != 0){

			for ($i=0; $i<$board_S['bo_upload_count']; $i++)
			{
				if(is_array($bf_no_array) && !in_array($i, $bf_no_array)){
					$new_bf_no = $i;
					break;
				}

			}

		}

		$file_info = $_FILES['Filedata'];
		if(preg_match('/^=\?UTF-8\?B\?(.+)\?=$/i', $file_info['name'], $match))
		{
			$file_name_temp = base64_decode(strtr($match[1], ':', '/'));
		}

		if($file_count == $board_S['bo_upload_count']){
			$error = true;
			$error_m = $file_name_temp;
		}

		$tmp_file  = $file_info['tmp_name'];
		$filesize  = $file_info['size'];
		$filename  = $file_name_temp;
		$filename  = get_safe_filename($filename);
		

		// 서버에 설정된 값보다 큰파일을 업로드 한다면
		if ($filename) {
			if ($file_info['error'] == 1) {
				$error = true;
				$error_m = $file_name_temp;
			}
			else if ($file_info['error'] != 0) {
				$error = true;
				$error_m = $file_name_temp;
			}
		}

		if (is_uploaded_file($tmp_file)) {
			// 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
			if (!$is_admin && $filesize > $board_S['bo_upload_size']) {
				$error = true;
				$error_m = $file_name_temp;
			}

			//=================================================================\
			// 090714
			// 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
			// 에러메세지는 출력하지 않는다.
			//-----------------------------------------------------------------
			$timg = @getimagesize($tmp_file);
			// image type
			if ( preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
				 preg_match("/\.({$config['cf_flash_extension']})$/i", $filename) ) {
				if ($timg['2'] < 1 || $timg['2'] > 16){
					$error = true;
					$error_m = $file_name_temp;
				}
			}

			$upload['image'] = $timg;
			// 프로그램 원래 파일명
			$upload['source'] = $filename;
			$upload['filesize'] = $filesize;

			//=================================================================
			if($error){

				$array = array("data" => $error_m);

				$json = "";

				$json = json_encode($array);

				header('Content-Type: application/json');
				echo $json;


			}else{
				// 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
				$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

				shuffle($chars_array);
				$shuffle = implode('', $chars_array);

				// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
				$upload['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

				$dest_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$upload['file'];

				// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
				$error_code = move_uploaded_file($tmp_file, $dest_file) or die($file_info['error']);

				// 올라간 파일의 퍼미션을 변경합니다.
				chmod($dest_file, G5_FILE_PERMISSION);

				if (!get_magic_quotes_gpc()) {
					$upload['source'] = addslashes($upload['source']);
				}

				
				$gnupushapp_file_wr_id = get_session('gnupushapp_file_wr_id');

				$query = "select count(*) as cnt from {$g5['board_file_table']} 
								where bo_table = '{$bo_table}'
                                and wr_id = '{$gnupushapp_file_wr_id}'
                                and bf_no = '{$new_bf_no}' ";
				$row = sql_fetch($query);
				if($row['cnt']>0){

					$sql = " update {$g5['board_file_table']}
                        set bf_source = '{$upload['source']}',
						 bf_file = '{$upload['file']}',
						 bf_content = '',
						 bf_download = 0,
						 bf_filesize = '{$upload['filesize']}',
						 bf_width = '{$upload['image']['0']}',
						 bf_height = '{$upload['image']['1']}',
						 bf_type = '{$upload['image']['2']}',
						 bf_datetime = '".G5_TIME_YMDHIS."',
						 bf_rstring = '{$r_num}'
                      where bo_table = '{$bo_table}'
                                and wr_id = '{$gnupushapp_file_wr_id}'
                                and bf_no = '{$new_bf_no}' ";

				}else{

					$sql = " insert into {$g5['board_file_table']}
								set bo_table = '{$bo_table}',
									 wr_id = '{$gnupushapp_file_wr_id}',
									 bf_no = '{$new_bf_no}',
									 bf_source = '{$upload['source']}',
									 bf_file = '{$upload['file']}',
									 bf_content = '',
									 bf_download = 0,
									 bf_filesize = '{$upload['filesize']}',
									 bf_width = '{$upload['image']['0']}',
									 bf_height = '{$upload['image']['1']}',
									 bf_type = '{$upload['image']['2']}',
									 bf_datetime = '".G5_TIME_YMDHIS."',
									 bf_rstring = '{$r_num}' ";

				}
				
				sql_query($sql);

				$image_src = G5_DATA_URL.'/file/'.$bo_table.'/'.urlencode($upload['file']);

				set_session('gnupushapp_file_img_src', $image_src);
				set_session('gnupushapp_file_count', $file_count);
				set_session('gnupushapp_file_bf_no', $new_bf_no);
				set_session('gnupushapp_file_source', $upload['source']);
				set_session('gnupushapp_file_size', $upload['filesize']);

				$array = array("data" => "ok");

				$json = "";

				$json = json_encode($array);

				header('Content-Type: application/json; charset=utf-8');
				header('Content-Length: ' . mb_strlen($json));
				echo $json;


			}

			
		}
		
	}
}

exit();

?>