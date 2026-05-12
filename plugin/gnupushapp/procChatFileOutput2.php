<?php
include_once('./_common.php');

// clean the output buffer
ob_end_clean();

$gnu_config = get_gnupushapp_config();

$filedown_id = htmlspecialchars($_REQUEST['filedown_id']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);
$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$mb_id = htmlspecialchars($_REQUEST['mb_id']);
$random = htmlspecialchars($_REQUEST['random']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword && $reg_id)
{
	$row_t = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$reg_id}' and gpci_mb_id = '{$mb_id}' ");

	if($row_t['cnt'] != 0)
	{
		
		$row_tm = sql_fetch(" select * from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$reg_id}' and gpci_mb_id = '{$mb_id}' ");
		$random_origin = $row_tm['gpci_random'];

		if($random == $random_origin)
		{

			$row_tmp = sql_fetch(" select * from g5_gnupushapp_filedown where ggf_keypass = '$filedown_id' and ggf_downloadok = 'N' ");

			if($row_tmp['ggf_keypass']){

				$filename = $row_tmp['ggf_chatFN'];
				$file_path_name = $row_tmp['ggf_chatOFN'];
				$gpc_ix = $row_tmp['ggf_chat_ix'];

				$sqld = " update g5_gnupushapp_filedown set ggf_downloadok = 'Y' where ggf_keypass = '{$row_tmp['ggf_keypass']}' ";
				sql_query($sqld);

				$file_dir = G5_DATA_PATH.'/gnupushchat/'.$gpc_ix;
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

			}
		}
	}
}

?>
