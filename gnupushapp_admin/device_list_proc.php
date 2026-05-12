<?php
include_once('../common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$gnu_config = get_gnupushapp_config();

if ($_POST['action_sort'] == "clearance") {
	$data = array("m_page" => 0);
	$gp_target_url = "#";
	$gp_target_title = "등록기기정리";
	
	if($gnu_config['push_m'] != 'S')
	{
		gnu_send_socket($data, 'delete', 'delete', $gp_target_url, $gp_target_title);
	}else{
		sync_proc_push($data, 'delete', 'delete', $gp_target_url, $gp_target_title);
	}


} else if ($_POST['action_sort'] == "push") {

	if (!count($_POST['chk'])) {
		alert("선택된 기기가 없습니다.");
	}

	if(!$_POST['push_title'] || !$_POST['push_content']){
		alert("제목과 내용을 적어주세요.");
	}

	$use_profile = "false";
	$profile_link = "none";
	$title1 = $_POST['push_title'];
	$content = $_POST['push_content'];
	$address = $_POST['push_link'];

	if(!$address) $address = G5_URL;

	$pushstyle = "normal";
	$image_src = "none";

	$gnu_config = get_gnupushapp_config();
	
	if($gnu_config['push_style'] == "Y")
	{
		$pushstyle = "big_text";
	}

	$file_info = $_FILES['push_img'];
	$rando = get_random_string_gnu('5');

	if($file_info['tmp_name'] && is_uploaded_file($file_info['tmp_name']))
	{
		@mkdir(G5_DATA_PATH."/gnupushapp", G5_DIR_PERMISSION);
		@chmod(G5_DATA_PATH."/gnupushapp", G5_DIR_PERMISSION);
		$file_ext_array = explode(".",$file_info['name']);
		$count_file = count($file_ext_array)-1;
		$file_ext = $file_ext_array[$count_file];
		$dest_path = G5_DATA_PATH."/gnupushapp/".$rando.".".$file_ext;

		if(@move_uploaded_file($file_info['tmp_name'], $dest_path))
		{
			$pushstyle = "big_picture";
			$image_src = G5_DATA_URL."/gnupushapp/".$rando.".".$file_ext;
		}

	}
	if($_POST['use_marketing'] == "Y"){
		$ticker = "(광고) 새로운 알림이 있습니다.";
	}else{
		$ticker = "새로운 공지사항이 있습니다.";
	}
	$bottom_text = $address;
	$banner = "headsup";
	$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

	$newRegIdsIbs1 = array();
	$newRegIdsIbm1 = array();
	$newRegIdsIs1 = array();
	$newRegIdsIm1 = array();
	$devices_select1 = array();

	$etc1 = $etc1 . "ab&#banoticeab&#ba" . $banner;
	$j = 1;
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
		$device_info = get_device_info_by_regid($_POST['chk'][$i]);

		$send_ok = false;

		if($_POST['use_marketing'] == "Y"){

			if($device_info['gpr_setting_marketing'] == 'Y')
			{
				$send_ok = true;
			}

		}else{

			if($device_info['gpr_setting_notice'] == 'Y')
			{
				$send_ok = true;
			}

		}

		if($send_ok)
		{

			if($device_info['gpr_sort'] == 'I'){
				$other_setting_array = unserialize($device_info['gpr_other_setting']);
			}

			if($device_info['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
			{
				if($gnu_config['build_sort'] == 'A' || $gnu_config["pushmsg"] == "Y")
				{
					$count_badge = getBadge_by_mb_id($device_info['gpr_mb_id']);
					$count_badge++;
					$stringBadge = "badgeN" . strval($count_badge);
					if($device_info['gpr_sort'] == 'I'){
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIbs1[$stringBadge][] = $device_info['gpr_reg_id'];
						}
						else
						{
							$newRegIdsIbm1[$stringBadge][] = $device_info['gpr_reg_id'];
						}

					}else{
						$devices_select1[floor($j/1000)][] = $device_info['gpr_reg_id'];
					}
				}
				else
				{
					if($device_info['gpr_sort'] == 'I'){
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIs1[floor($j/1000)][] = $device_info['gpr_reg_id'];
						}
						else
						{
							$newRegIdsIm1[floor($j/1000)][] = $device_info['gpr_reg_id'];
						}

					}else{
						$devices_select1[floor($j/1000)][] = $device_info['gpr_reg_id'];
					}
				}

				insert_Notification_list("notice", '', '', $title1, $address, $device_info['gpr_mb_id'], '', '', '');
			}
			else
			{
				if($device_info['gpr_sort'] == 'I'){
					if($other_setting_array[0] == 'sound')
					{
						$newRegIdsIs1[floor($j/1000)][] = $device_info['gpr_reg_id'];
					}
					else
					{
						$newRegIdsIm1[floor($j/1000)][] = $device_info['gpr_reg_id'];
					}

				}else{
					$devices_select1[floor($j/1000)][] = $device_info['gpr_reg_id'];
				}
			}
			$j++;

		}
    }
	$send1 = "";

	$rnum = get_random_string_gnu('5');
	$keypass = substr(md5(date('YmdHis')), 0, 25) . $rnum;

	if(count($devices_select1) > 0 || count($newRegIdsIbs1) > 0 || count($newRegIdsIbm1) > 0 || count($newRegIdsIs1) > 0 || count($newRegIdsIm1) > 0)
	{
		$send1 = gnupushsend($devices_select1, $title1, $content, $address, $etc1, $newRegIdsIbs1, $newRegIdsIbm1, $newRegIdsIs1, $newRegIdsIm1, array(), array(), "normal", "important", array(), $keypass);
	}

	$total_push = 0;
	$success_push = 0;
	$error_push = 0;
	
	if($send1 != "")
	{
		$send1_array = explode("-", $send1);
		$total_push = $total_push + $send1_array[0];
		$success_push = $success_push + $send1_array[1];
		$error_push = $error_push + $send1_array[2];
	}

	if($total_push == 0)
	{

	}
	else
	{

		$data_text = "총발송량 : " . $total_push . "  /  성공 : " . $success_push . "  /  에러 및 삭제 : " . $error_push;
		
		$gp_target_url = $address;
		$gp_target_title = $title1;

		sql_query(" INSERT INTO g5_gnupushapp_push 
						set gp_pushid = '{$keypass}',
						gp_issend = 'Y',
						gp_target_browser = 'send',
						gp_text = '{$data_text}',
						gp_push_date = '".G5_TIME_YMDHIS."',
						gp_type = 'send',
						gp_target_url = '{$gp_target_url}',
						gp_target_title = '{$gp_target_title}'
						", true);
	}
	


}else if ($_POST['action_sort'] == "delete") {

	if (!count($_POST['chk'])) {
		alert("삭제하실 항목을 하나 이상 체크하세요.");
	}

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        $k = $_POST['chk'][$i];
		$sql = " delete from g5_gnupushapp_gcmregid where gpr_reg_id = '{$k}' ";
		sql_query($sql);

		$sql = " delete from g5_gnupushapp_subscribe where gss_reg_id = '{$k}' ";
		sql_query($sql);
        
    }
}


goto_url('device_list.php', false);

?>
