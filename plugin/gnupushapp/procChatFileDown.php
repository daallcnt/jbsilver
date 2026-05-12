<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$str_mp = substr($gnu_config['masterpassword'], 0, 15);
$nowDate = htmlspecialchars($_REQUEST['nowDate']);
$mb_id = htmlspecialchars($_REQUEST['mb_id']);
$key = htmlspecialchars($_REQUEST['key']);
$random = htmlspecialchars($_REQUEST['random']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);
$filedown_id = "none";
$filename = "none";
$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$reg_id = get_session('reg_id');

if($reg_id && $str_mp == $masterpassword)
{
	$device_in = get_device_info_by_regid($reg_id);

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



	$row_t = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$reg_id}' and gpci_mb_id = '{$mb_id}' ");

	if($row_t['cnt'] != 0)
	{
		
		$row_tm = sql_fetch(" select * from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$reg_id}' and gpci_mb_id = '{$mb_id}' ");
		$random_origin = $row_tm['gpci_random'];

		if($random == $random_origin)
		{
			$row_tmpdd = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') ");

			if($row_tmpdd['cnt'] > 0)
			{

				$row_result_a = sql_query(" select * from g5_gnupushapp_chat where (gpc_mb_id1 = '{$my_id}' and gpc_mb_id2 = '{$mb_id}') or (gpc_mb_id1 = '{$mb_id}' and gpc_mb_id2 = '{$my_id}') ");

				for ($i=0; $row_tmp=sql_fetch_array($row_result_a); $i++)
				{

					$recentDate = date("Y-m-d", strtotime($row_tmp['gpc_regdate']));

					if($recentDate == $nowDate)
					{
				
						$data_array = unserialize(base64_decode($row_tmp['gpc_chat']));

						$val = $data_array[$key];

						$is_file = $val['is_file'];

						if($is_file == "Y")
						{

							$filename = $val['content'];

							$recentDate = date("YmdHms",strtotime($val['reg_date']));

							$filename = $recentDate . $filename;

							$file_path_name = $val['file_name'];

							$rnum = get_random_string_gnu(30);
							$filedown_id = date('Ymd') . $rnum;

							sql_query(" INSERT INTO g5_gnupushapp_filedown 
								set ggf_keypass = '$filedown_id',
								ggf_bo_table = 'none',
								ggf_wr_id = '1',
								ggf_no = '1',
								ggf_chatOFN = '{$file_path_name}',
								ggf_chatFN = '{$filename}',
								ggf_chat_ix = '{$row_tmp['gpc_ix']}',
								ggf_downloadok = 'N',
								ggf_regdate = '".G5_TIME_YMDHIS."'
								", true);

						}

					}
				}
			}
		}
	}
}

$array = array("filedown_id" => $filedown_id, "filename" => $filename);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;

exit();



?>