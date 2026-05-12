<?php
include_once('./_common.php');

// clean the output buffer
ob_end_clean();

$gnu_config = get_gnupushapp_config();

$room_id = htmlspecialchars($_REQUEST['room_id']);
$cc_ix = htmlspecialchars($_REQUEST['cc_ix']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

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

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword && $is_member)
{
	$my_id = $_SESSION['ss_mb_id'];

    $row_tmp = sql_fetch(" select * from g5_gnupushapp_newchatting_content where cc_ix = '{$cc_ix}' ");
    
    $row_result_d = sql_fetch(" select count(*) as cnt from g5_gnupushapp_newchatting_room_joinlist where cr_ix = '{$room_id}' and mb_id = '{$my_id}' and c_status in ('join','nowjoin')");
    
    if($row_tmp['cc_ix'] && $row_result_d['cnt'] > 0 && $row_tmp['cr_ix'] == $room_id)
	{

		$is_file = $row_tmp['is_file'];

		$filename = $row_tmp['content'];

		$recentDate = date("YmdHms",strtotime($row_tmp['regdate']));

		$filename = $recentDate . $filename;

		$file_path_name = $row_tmp['filepath'];

		$is_image = $row_tmp['is_image'];

		if($is_file == "Y")
		{
			$file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_tmp['cc_ix'];
			$mb_image_path = $file_dir . '/'  . $file_path_name;
			if(file_exists($mb_image_path))
			{

				$filepath = addslashes($mb_image_path);
                $original = urlencode($filename); // SIR 잉끼님 제안코드

				header("content-type: doesn/matter");
				header("content-length: ".filesize("$filepath"));
				header("content-disposition: attachment; filename=\"$original\"");
				header("Content-Transfer-Encoding: binary\n");
				header("pragma: no-cache");
				header("expires: 0");
				flush();

				$fp = fopen($filepath, 'rb');

				$download_rate = 10;

				while(!feof($fp)) {

					print fread($fp, round($download_rate * 1024));
					flush();
					usleep(1000);
				}
				fclose ($fp);
				flush();
			}
			else
			{
				exit();
			}
		}
		else
		{
			exit();
		}
	}
	else
	{
		exit();
	}

}
else
{
	exit();
}
?>
