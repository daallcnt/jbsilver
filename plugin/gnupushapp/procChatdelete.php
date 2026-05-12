<?php
include_once('./_common.php');

$gnu_config = get_gnupushapp_config();

$mb_id = htmlspecialchars($_REQUEST['mb_id']);
$today = htmlspecialchars($_REQUEST['today']);
$key = htmlspecialchars($_REQUEST['key']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword && $_SESSION['reg_id'])
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
			}

		}
	}

	$row_tmp = sql_fetch(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') ");

	$row_tmpdd = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') ");

	if($row_tmpdd['cnt'] > 0)
	{

		$row_result_a = sql_query(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') ");

		for ($i=0; $row_tmp=sql_fetch_array($row_result_a); $i++)
		{

			$recentDate = date("Y-m-d", strtotime($row_tmp['gpc_regdate']));

			if($recentDate == $today)
			{

				$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));

				$val = $data_array[$key];
				if($val['is_file'] == "Y")
				{
					$file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_tmp['gpc_ix'];
					$mb_image_path = $file_dir . '/'  . $val['file_name'];
					if(file_exists($mb_image_path)){
						@unlink($mb_image_path);
					}

					if($val['is_image'] == "Y")
					{
						$fileDate = date("YmdHms",strtotime($val['reg_date']));

						$filename = $fileDate . $val['content'];
						$target_path = G5_DATA_PATH.'/gnupushchat/thumbnail/' . $row_tmp['gpc_ix'] . ' / ' . $filename;
						if(file_exists($target_path)){
							@unlink($target_path);
						}
					}
				}

				unset($data_array[$key]);

				$is_notread_exist = "N";

				foreach($data_array as $key_s => $val_s)
				{
					if($val_s['read'] == "N")
					{
						$is_notread_exist = "Y";
					}
				}

				$data = base64_encode(serialize($data_array));

				$sql = " update g5_gnupushapp_chat
					set gpc_chat = '{$data}',
					gpc_notread = '{$is_notread_exist}',
					gpc_lastdate = '".G5_TIME_YMDHIS."'
					where gpc_ix = '{$row_tmp['gpc_ix']}' ";
				sql_query($sql);

			}
		}
	}

}

exit();

?>