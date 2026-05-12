<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$push_div_num = (int)$gnu_config['push_div_num'];

$keypass = htmlspecialchars($_POST['data']);

if(!$keypass) return;

$keypass = strip_tags($keypass);

sleep(2);

$row = sql_fetch(" select * from g5_gnupushapp_push where gp_pushid = '{$keypass}' ");

if(!$row['gp_text']) return;

$data_array = unserialize(base64_decode($row['gp_text']));

$gp_type = $row['gp_type'];

switch($row['gp_type'])
{

	case "quicksend" :

		$member_ids = $data_array['member_ids'];
		$title = $data_array['title'];
		$content = $data_array['content'];
		$address = $data_array['address'];
		$etc = $data_array['etc'];
		$sort = $data_array['sort'];
		$response = $data_array['response'];
		$typeP = $data_array['typeP'];

		$a_member_ids = array_unique(array_values(array_map('trim',$member_ids)));

		$total_push = 0;
		$success_push = 0;
		$error_push = 0;
		$remove_ids = array();

		$etc_array = explode("ab&#ba", $etc);

		// sort는 4가지 종류 : newpost, notice, must, youngcart

		$sql_setting = "";

		if(!$sort) $sort = "must";

		if($sort != "must"){
			if($sort == "youngcart"){
				$sql_setting = "and gpr_youngcart_setting = 'true' ";
			}else{
				$sort_sql = "%".$sort . "_true%";
				$sql_setting = "and gpr_setting like '{$sort_sql}'";
			}
		}

		$mb_ids = "";

		for ($i=0; $i<count($a_member_ids); $i++)
		{
			if($mb_ids == ""){
				$mb_ids = "'".$a_member_ids[$i]."'";
			}else{
				$mb_ids .= ",'".$a_member_ids[$i]."'";
			}
		}

		$devices_select1 = array();
		$newRegIdsIbs = array(); //badge & sound
		$newRegIdsIbm = array(); //badge & mute
		$newRegIdsIs = array(); // sound
		$newRegIdsIm = array(); // mute
		$newRegIdsId = array(); // chat data

		$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting from g5_gnupushapp_gcmregid where gpr_mb_id in ({$mb_ids}) $sql_setting ";
		$devices_select_array = sql_query($query);

		if($typeP == "chat" || $typeP == "read")
		{
			$my_id = get_my_chat_id();
		}

		$chatpush = "true";

		for ($i=1; $row=sql_fetch_array($devices_select_array); $i++)
		{
			if($row['gpr_mb_id'] && $gnu_config["mypushlist"] == "Y")
			{
				if($row['gpr_sort'] == 'I'){
					$other_setting_array = unserialize($row['gpr_other_setting']);
					if(count($other_setting_array) > 3) $chatpush = $other_setting_array[3];
				}


				if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
				{
					$count_badge = getBadge_by_mb_id($row['gpr_mb_id']);
					$count_badge++;
					$stringBadge = "badgeN" . strval($count_badge);
					if($row['gpr_sort'] == 'I'){

						if($typeP == "chat"){
								
							$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
							
							if($row_tmp['cnt'] == 0){
								if($chatpush == 'true'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs[$stringBadge][] = $row['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIbm[$stringBadge][] = $row['gpr_reg_id'];
									}
								}
							}else{
								$newRegIdsId[floor($i/1000)][] = $row['gpr_reg_id'];
							}
						}else if($typeP == "read"){
							$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
							if($row_tmp['cnt'] != 0){
								$newRegIdsId[floor($i/1000)][] = $row['gpr_reg_id'];
							}
						}else{
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIbs[$stringBadge][] = $row['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIbm[$stringBadge][] = $row['gpr_reg_id'];
							}
						}

					}else{
						$devices_select1[floor($i/1000)][] = $row['gpr_reg_id'];
					}
				}
				else
				{
					if($row['gpr_sort'] == 'I'){

						if($typeP == "chat"){
								
							$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
							if($row_tmp['cnt'] == 0){
								if($chatpush == 'true'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs[floor($i/1000)][] = $row['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm[floor($i/1000)][] = $row['gpr_reg_id'];
									}
								}

							}else{
								$newRegIdsId[floor($i/1000)][] = $row['gpr_reg_id'];
							}
								
						}else if($typeP == "read"){
							$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
							if($row_tmp['cnt'] != 0){
								$newRegIdsId[floor($i/1000)][] = $row['gpr_reg_id'];
							}
						}else{
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIs[floor($i/1000)][] = $row['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIm[floor($i/1000)][] = $row['gpr_reg_id'];
							}
						}
					}else{
						$devices_select1[floor($i/1000)][] = $row['gpr_reg_id'];
					}
				}

				if($etc_array[4] == $title){
					$msg_subject = $etc_array[4];
				}else{
					$msg_subject = $etc_array[4]." [".$title."]";
				}
				if($response) insert_Notification_list('notice', '', '', $msg_subject, $address, $row['gpr_mb_id'], '', '', '');
			}
			else
			{
				if($row['gpr_sort'] == 'I'){

					if($typeP == "chat"){

						$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
						
						if($row_tmp['cnt'] == 0){
							if($chatpush == 'true'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs[floor($i/1000)][] = $row['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIm[floor($i/1000)][] = $row['gpr_reg_id'];
								}
							}
						}else{
							$newRegIdsId[floor($i/1000)][] = $row['gpr_reg_id'];
						}
					}else if($typeP == "read"){
						$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
						if($row_tmp['cnt'] != 0){
							$newRegIdsId[floor($i/1000)][] = $row['gpr_reg_id'];
						}
					}else{
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIs[floor($i/1000)][] = $row['gpr_reg_id'];
						}
						else
						{
							$newRegIdsIm[floor($i/1000)][] = $row['gpr_reg_id'];
						}
						
					}
				}else{
					$devices_select1[floor($i/1000)][] = $row['gpr_reg_id'];
				}
			}
		}

		$rnum = get_random_string_gnu('5');
		$keypass = substr(md5(date('YmdHis')), 0, 25) . $rnum;

		$devices_selectIphone = array();

		$send = gnupushsend($devices_select1, $title, $content, $address, $etc, $newRegIdsIbs, $newRegIdsIbm, $newRegIdsIs, $newRegIdsIm, $devices_selectIphone, $newRegIdsId, $typeP,"important",array(),$keypass);
		$total_push = 0;
		$success_push = 0;
		$error_push = 0;

		
		if($send != "")
		{
			$send_array = explode("-", $send);
			$total_push = $total_push + $send_array[0];
			$success_push = $success_push + $send_array[1];
			$error_push = $error_push + $send_array[2];
		}

		if($total_push != 0)
		{

			$data_text = "총발송량 : " . $total_push . "  /  성공 : " . $success_push . "  /  에러 및 삭제 : " . $error_push;

			
			$gp_target_url = $address;
			$gp_target_title = cut_str(strip_tags($title." / ".$content),150,'');

			sql_query(" INSERT INTO g5_gnupushapp_push 
							set gp_pushid = '{$keypass}',
							gp_issend = 'Y',
							gp_target_browser = 'quick_send',
							gp_text = '{$data_text}',
							gp_push_date = '".G5_TIME_YMDHIS."',
							gp_type = '{$sort}',
							gp_target_url = '{$gp_target_url}',
							gp_target_title = '{$gp_target_title}'
							", true);

		}

		break;

	case "quicksendr" :

		$reg_ids = $data_array['reg_ids'];
		$title = $data_array['title'];
		$content = $data_array['content'];
		$address = $data_array['address'];
		$etc = $data_array['etc'];
		$sort = $data_array['sort'];
		$response = $data_array['response'];
		$typeP = $data_array['typeP'];

		$a_reg_ids = array_unique(array_values(array_map('trim',$reg_ids)));

		$total_push = 0;
		$success_push = 0;
		$error_push = 0;
		$remove_ids = array();

		$etc_array = explode("ab&#ba", $etc);

		// sort는 4가지 종류 : newpost, notice, must, youngcart

		$sql_setting = "";

		if(!$sort) $sort = "must";

		if($sort != "must"){
			if($sort == "youngcart"){
				$sql_setting = "and gpr_youngcart_setting = 'true' ";
			}else{
				$sort_sql = "%".$sort . "_true%";
				$sql_setting = "and gpr_setting like '{$sort_sql}'";
			}
		}

		$mb_ids = "";

		for ($i=0; $i<count($a_reg_ids); $i++)
		{
			$row_tmp = sql_fetch("select count(*) as 'cnt' from g5_gnupushapp_gcmregid where gpr_reg_id = '{$a_reg_ids[$i]}' $sql_setting ");
			if($row_tmp['cnt'] == 0)
			{
				if(($key = array_search($a_reg_ids[$i], $a_reg_ids)) !== false)
				{
					unset($a_reg_ids[$key]);
				}
			}
		}

		$devices_select1 = array();
		$newRegIdsIbs = array(); //badge & sound
		$newRegIdsIbm = array(); //badge & mute
		$newRegIdsIs = array(); // sound
		$newRegIdsIm = array(); // mute
		$newRegIdsId = array(); // chat data

		$chatpush = "true";

		if($typeP == "chat" || $typeP == "read")
		{
			$my_id = get_my_chat_id();
		}

		for ($i=0; $i<count($a_reg_ids); $i++)
		{
			$j = $i + 1;
			$row = get_device_info_by_regid($a_reg_ids[$i]);
			if($row['gpr_mb_id'] && $gnu_config["mypushlist"] == "Y")
			{
				
				if($row['gpr_sort'] == 'I'){
					$other_setting_array = unserialize($row['gpr_other_setting']);
					if(count($other_setting_array) > 3) $chatpush = $other_setting_array[3];
				}


				if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
				{
					$count_badge = getBadge_by_mb_id($row['gpr_mb_id']);
					$count_badge++;
					$stringBadge = "badgeN" . strval($count_badge);
					if($row['gpr_sort'] == 'I'){

						if($typeP == "chat"){
							
							$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
							
							if($row_tmp['cnt'] == 0){
								if($chatpush == 'true'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs[$stringBadge][] = $row['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIbm[$stringBadge][] = $row['gpr_reg_id'];
									}
								}
							}else{
								$newRegIdsId[floor($j/1000)][] = $row['gpr_reg_id'];
							}
						}else if($typeP == "read"){

							$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
							if($row_tmp['cnt'] != 0){
								$newRegIdsId[floor($j/1000)][] = $row['gpr_reg_id'];
							}

						}else{
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIbs[$stringBadge][] = $row['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIbm[$stringBadge][] = $row['gpr_reg_id'];
							}
						}

					}else{
						$devices_select1[floor($j/1000)][] = $row['gpr_reg_id'];
					}
				}
				else
				{
					if($row['gpr_sort'] == 'I'){

						if($typeP == "chat"){
							
							$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
							
							if($row_tmp['cnt'] == 0){
								if($chatpush == 'true'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs[floor($j/1000)][] = $row['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm[floor($j/1000)][] = $row['gpr_reg_id'];
									}
								}
							}else{
								$newRegIdsId[floor($j/1000)][] = $row['gpr_reg_id'];
							}
								
						}else if($typeP == "read"){
							$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
							if($row_tmp['cnt'] != 0){
								$newRegIdsId[floor($j/1000)][] = $row['gpr_reg_id'];
							}
						}else{

							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIs[floor($j/1000)][] = $row['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIm[floor($j/1000)][] = $row['gpr_reg_id'];
							}
						}
					}else{
						$devices_select1[floor($j/1000)][] = $row['gpr_reg_id'];
					}
				}

				if($etc_array[4] == $title){
					$msg_subject = $etc_array[4];
				}else{
					$msg_subject = $etc_array[4]." [".$title."]";
				}
				if($response) insert_Notification_list('notice', '', '', $msg_subject, $address, $row['gpr_mb_id'], '', '', '');
			}
			else
			{
				if($row['gpr_sort'] == 'I'){

					if($typeP == "chat"){
							
						$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
						
						if($row_tmp['cnt'] == 0){
							if($chatpush == 'true'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs[floor($j/1000)][] = $row['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIm[floor($j/1000)][] = $row['gpr_reg_id'];
								}
							}
						}else{
							$newRegIdsId[floor($j/1000)][] = $row['gpr_reg_id'];
						}
					}else if($typeP == "read"){

						$row_tmp = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$row['gpr_reg_id']}' and gpci_mb_id = '{$my_id}' ");
						if($row_tmp['cnt'] != 0){
							$newRegIdsId[floor($j/1000)][] = $row['gpr_reg_id'];
						}

					}else{
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIs[floor($j/1000)][] = $row['gpr_reg_id'];
						}
						else
						{
							$newRegIdsIm[floor($j/1000)][] = $row['gpr_reg_id'];
						}
					}

				}else{
					$devices_select1[floor($j/1000)][] = $row['gpr_reg_id'];
				}
			}
		}

		$rnum = get_random_string_gnu('5');
		$keypass = substr(md5(date('YmdHis')), 0, 25) . $rnum;

		$send = gnupushsend($devices_select1, $title, $content, $address, $etc, $newRegIdsIbs, $newRegIdsIbm, $newRegIdsIs, $newRegIdsIm, $devices_selectIphone = array(), $newRegIdsId, $typeP,"important",array(),$keypass);

		$total_push = 0;
		$success_push = 0;
		$error_push = 0;

		
		if($send != "")
		{
			$send_array = explode("-", $send);
			$total_push = $total_push + $send_array[0];
			$success_push = $success_push + $send_array[1];
			$error_push = $error_push + $send_array[2];
		}

		if($total_push != 0)
		{

			$data_text = "총발송량 : " . $total_push . "  /  성공 : " . $success_push . "  /  에러 및 삭제 : " . $error_push;

			
			$gp_target_url = $address;
			$gp_target_title = cut_str(strip_tags($title." / ".$content),150,'');

			sql_query(" INSERT INTO g5_gnupushapp_push 
							set gp_pushid = '{$keypass}',
							gp_issend = 'Y',
							gp_target_browser = 'quick_send',
							gp_text = '{$data_text}',
							gp_push_date = '".G5_TIME_YMDHIS."',
							gp_type = '{$sort}',
							gp_target_url = '{$gp_target_url}',
							gp_target_title = '{$gp_target_title}'
							", true);

		}

		break;

	

}


exit();

?>