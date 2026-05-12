<?php
include_once('./_common.php');

// clean the output buffer
ob_end_clean();

$gnu_config = get_gnupushapp_config();

$nowDate = htmlspecialchars($_REQUEST['nowDate']);
$mb_id = htmlspecialchars($_REQUEST['mb_id']);
$key = htmlspecialchars($_REQUEST['key']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword && $is_member)
{

	$my_id = $_SESSION['ss_mb_id'];

	$row_tmp = sql_fetch(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') and DATE(gpc_regdate) = '{$nowDate}' ");

	if($row_tmp)
	{
		$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));

		$val = $data_array[$key];

		$is_file = $val['is_file'];

		$filename = $val['content'];

		$recentDate = date("YmdHms",strtotime($val['reg_date']));

		$filename = $recentDate . $filename;

		$file_path_name = $val['file_name'];

		$is_image = $val['is_image'];

		if($is_file == "Y")
		{
			$file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_tmp['gpc_ix'];
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
