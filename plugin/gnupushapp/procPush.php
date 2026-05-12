<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

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

$returnEnd = false;

$devices_select1 = array();
$devices_select2 = array();
$devices_select3 = array();
$devices_select4 = array();

$devices_newAndroid_select1 = array();
$devices_newAndroid_select2 = array();
$devices_newAndroid_select3 = array();
$devices_newAndroid_select4 = array();

$devices_selectIphone = array();

$newRegIdsA1 = array();
$newRegIdsA2 = array();
$newRegIdsA3 = array();
$newRegIdsA4 = array();

$newRegIdsIbs1 = array(); //badge & sound
$newRegIdsIbs2 = array(); //badge & sound
$newRegIdsIbs3 = array(); //badge & sound
$newRegIdsIbs4 = array(); //badge & sound

$newRegIdsIbm1 = array(); //badge & mute
$newRegIdsIbm2 = array(); //badge & mute
$newRegIdsIbm3 = array(); //badge & mute
$newRegIdsIbm4 = array(); //badge & mute

$newRegIdsIs1 = array(); // sound
$newRegIdsIs2 = array(); // sound
$newRegIdsIs3 = array(); // sound
$newRegIdsIs4 = array(); // sound

$newRegIdsIm1 = array(); // mute
$newRegIdsIm2 = array(); // mute
$newRegIdsIm3 = array(); // mute
$newRegIdsIm4 = array(); // mute

$pushchannel = "important";
$pushchannel2 = "important";
$pushchannel3 = "important";
$pushchannel4 = "important";

$banner = "false";

$response_array_mb_id = array();

switch($row['gp_type'])
{

	case "curl" :

		$returnEnd = true;
		$ch = getnewcurl();
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_array));

		// Execute post
		$gcm_result = curl_exec($ch);

		if(curl_error($ch))
		{
			$error_message = curl_error($ch);
			import_Error_device("curl error",$error_message);
		}

		$jsonArray = json_decode($gcm_result);
		$success_send = $jsonArray['success'];
		$error_ids = $jsonArray['failure'];
		$canonical_ids = $jsonArray['canonical_ids'];
		$total_ids = count($jsonArray['results']);

		$data_text = "총발송량 : " . $total_ids . "  /  성공 : " . $success_send . "  /  에러 및 삭제 : " . $error_ids;

		$sql = " update g5_gnupushapp_push
				set gp_issend = 'Y',
				gp_text = '{$data_text}'
				where gp_pushid = '{$keypass}' ";
		sql_query($sql);

		
		break;

	case "quicksend" :

		$returnEnd = true;

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
			$sql_setting = "and gpr_setting_" . $sort . " = 'Y' ";
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

		$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_mb_id in ({$mb_ids}) $sql_setting ";
		$devices_select_array = sql_query($query);

		if($typeP == "chat" || $typeP == "read")
		{
			$my_id = get_my_chat_id();
		}

		for ($i=1; $row=sql_fetch_array($devices_select_array); $i++)
		{
			if($row['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
			{
				if($row['gpr_sort'] == 'I'){
					$other_setting_array = unserialize($row['gpr_other_setting']);
				}

				$chatpush = "true";

				if($row['gpr_setting_chat'] == 'N') $chatpush = "false";

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
						if($row['gpr_os_version'] >= 8){
							$devices_newAndroid_select1[$stringBadge][] = $row['gpr_reg_id'];
						}else{
							$devices_select1[floor($i/1000)][] = $row['gpr_reg_id'];
						}
						
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
				if($response){
					if(!is_array($response_array_mb_id) || !in_array($row['gpr_mb_id'], $response_array_mb_id)){
						insert_Notification_list('notice', '', '', $msg_subject, $address, $row['gpr_mb_id'], '', '', '');
						array_push($response_array_mb_id, $row['gpr_mb_id']);
					}
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
		}

		$devices_selectIphone = array();

		$send = gnupushsend($devices_select1, $title, $content, $address, $etc, $newRegIdsIbs, $newRegIdsIbm, $newRegIdsIs, $newRegIdsIm, $devices_selectIphone, $newRegIdsId, $typeP, $pushchannel, $devices_newAndroid_select1, $keypass);
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

			$sql = " update g5_gnupushapp_push
						set gp_issend = 'Y',
						gp_text = '{$data_text}'
						where gp_pushid = '{$keypass}' ";
			sql_query($sql);

		}
		break;

	case "quicksendr" :

		$returnEnd = true;

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
			$sql_setting = "and gpr_setting_" . $sort . " = 'Y' ";
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

		if($typeP == "chat" || $typeP == "read")
		{
			$my_id = get_my_chat_id();
		}

		for ($i=0; $i<count($a_reg_ids); $i++)
		{
			$j = $i + 1;
			$row = get_device_info_by_regid($a_reg_ids[$i]);
			if($row['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
			{
				
				if($row['gpr_sort'] == 'I'){
					$other_setting_array = unserialize($row['gpr_other_setting']);
				}

				$chatpush = "true";

				if($row['gpr_setting_chat'] == 'N') $chatpush = "false";

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
						if($row['gpr_os_version'] >= 8){
							$devices_newAndroid_select1[$stringBadge][] = $row['gpr_reg_id'];
						}else{
							$devices_select1[floor($j/1000)][] = $row['gpr_reg_id'];
						}
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
				if($response){
					if(!is_array($response_array_mb_id) || !in_array($row['gpr_mb_id'], $response_array_mb_id)){
						insert_Notification_list('notice', '', '', $msg_subject, $address, $row['gpr_mb_id'], '', '', '');
						array_push($response_array_mb_id, $row['gpr_mb_id']);
					}
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
		}

		$devices_selectIphone = array();

		$send = gnupushsend($devices_select1, $title, $content, $address, $etc, $newRegIdsIbs, $newRegIdsIbm, $newRegIdsIs, $newRegIdsIm, $devices_selectIphone, $newRegIdsId, $typeP, $pushchannel, $devices_newAndroid_select1, $keypass);
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

			$sql = " update g5_gnupushapp_push
						set gp_issend = 'Y',
						gp_text = '{$data_text}'
						where gp_pushid = '{$keypass}' ";
			sql_query($sql);

		}
		break;

	case "new_product" :
		$it_id = $data_array['it_id'];
		$it_name = stripslashes($data_array['it_name']);
		$it_name = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $it_name);
		$it_explan = stripslashes($data_array['it_explan']);
		$it_explan = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $it_explan);
		$it_price = number_format($data_array['it_price']);
		$type_item_p = $data_array['type_item_p'];
		$ca_id = $data_array['category_item'];

		$pushchannel = "normal";

		$use_profile = "false";
		$profile_link = "none";

		$title1 = "";

		if($type_item_p != "none") $title1 .= $type_item_p." ";

		$title1 .= $it_name;
		$content = $it_explan;
		$address = G5_URL.'/shop/item.php?it_id='.$it_id;

		$pushstyle = "normal";
		$image_src = "none";
		
		if($gnu_config['push_style'] == "Y")
		{
			//첨부파일 가져오기

			$width=500;
			$height=250;

			$sqldd = " select it_id, it_img1, it_img2, it_img3, it_img4, it_img5, it_img6, it_img7, it_img8, it_img9, it_img10 from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
			$rowdd = sql_fetch($sqldd);

			if(!$rowdd['it_id'])
				return '';

			for($i=1;$i<=10; $i++) {
				$file = G5_DATA_PATH.'/item/'.$rowdd['it_img'.$i];
				if(is_file($file) && $rowdd['it_img'.$i]) {
					$size = @getimagesize($file);
					if($size[2] < 1 || $size[2] > 3)
						continue;

					$filename = basename($file);
					$filepath = dirname($file);
					$img_width = $size[0];
					$img_height = $size[1];

					break;
				}
			}

			if($img_width && !$height) {
				$height = round(($width * $img_height) / $img_width);
			}

			if($filename) {
				$thumb = thumbnail($filename, $filepath, $filepath, $width, $height, false, true, 'center', false, $um_value='80/0.5/3');
			}

			if($thumb) {
				$image_src = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
				$pushstyle = "big_picture";
			}else{
				$pushstyle = "big_text";
			}
		}

		$category_item = "";
		$len = strlen($ca_id) / 2;
		for ($i=1; $i<=$len; $i++)
		{
			$code = substr($ca_id,0,$i*2);

			$sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '$code' ";
			$rowddd = sql_fetch($sql);
			
			if ($ca_id == $code){
				$category_item .= $rowddd['ca_name'];
			}else{
				$category_item .= $rowddd['ca_name'] . ">";
			}
		}

		$bottom_text = $it_price . "원 / " . $category_item;
		$ticker = $gnu_config['youngcart_name']."에 새 상품이 등록되었습니다.";
		$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

		if($data_array['m_page'] == 0)
		{

			if($ca_id)
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where gss_ca_name = '{$ca_id}' and gss_is_youngcart = 'Y' and gss_post_subscribe_onoff = 'Y' ";
				$rowew = sql_fetch($query);
			}else{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where gpr_setting_youngcart = 'Y' and gpr_setting_youngcart_all = 'Y' ";
				$rowew = sql_fetch($query);
			}

			$devices_num = $rowew['cnt'];

			if($devices_num > $push_div_num)
			{
				$count_p = ceil($devices_num / $push_div_num);

				for($i=0;$i<$count_p;$i++)
				{
					$data_array['m_page'] = $i+1;
					$page_num = $i+1;
					$gp_target_url = G5_URL.'/shop/item.php?it_id='.$it_id;
					$gp_target_title = cut_str($it_name,50,'');
					$gp_target_title = $gp_target_title."[".$page_num."/".$count_p."]";
					gnu_send_socket($data_array, 'Youngcart5', 'new_product', $gp_target_url, $gp_target_title);
				}

			}
			else
			{

				if($ca_id)
				{
					$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where gss_ca_name = '{$ca_id}' and gss_is_youngcart = 'Y' and gss_post_subscribe_onoff = 'Y' ";
					$devices_select_array = sql_query($query);
				}else{
					$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_youngcart = 'Y' and gpr_setting_youngcart_all = 'Y' ";
					$devices_select_array = sql_query($query);
				}					

				for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
				{
					if($ca_id)
					{
						$data_other_setting = $rowddd['gss_other_setting'];
						$data_sort = $rowddd['gss_sort'];
						$data_mb_id = $rowddd['gss_mb_id'];
						$data_sync = $rowddd['gss_sync'];
						$data_reg_id = $rowddd['gss_reg_id'];
					}else{
						$data_other_setting = $rowddd['gpr_other_setting'];
						$data_sort = $rowddd['gpr_sort'];
						$data_mb_id = $rowddd['gpr_mb_id'];
						$data_sync = $rowddd['gpr_sync'];
						$data_reg_id = $rowddd['gpr_reg_id'];
					}
					if($data_sort == 'I'){
						$other_setting_array = unserialize($data_other_setting);
						$board_keyword = $other_setting_array[2];
						if(strlen($board_keyword) > 0)
						{
							if(strpos($board_keyword, ",") !== false)
							{
								$board_keyword_array = explode(',', $board_keyword);
								$keyword_exist = false;
								foreach($board_keyword_array as $value_keyword)
								{
									if(strpos($title1, $value_keyword) !== false || strpos($content, $value_keyword) !== false)
									{
										$keyword_exist = true;
									}
								}

								if(!$keyword_exist)
								{
									continue;
								}
							}
							else
							{
								if(!(strpos($title1, $board_keyword) !== false) && !(strpos($content, $board_keyword) !== false))
								{
									continue;
								}
							}

						}
					}

					if($data_sync != 'N' && $gnu_config["mypushlist"] == "Y")
					{
						if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
						{
							$count_badge = getBadge_by_mb_id($data_mb_id);
							$count_badge++;
							$stringBadge = "badgeN" . strval($count_badge);
							if($data_sort == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIbs1[$stringBadge][] = $data_reg_id;
								}
								else
								{
									$newRegIdsIbm1[$stringBadge][] = $data_reg_id;
								}

							}else{
								if($rowddd['gpr_os_version'] >= 8){
									$devices_newAndroid_select1[$stringBadge][] = $data_reg_id;
								}else{
									$devices_select1[floor($i/1000)][] = $data_reg_id;
								}
							}
						}
						else
						{
							if($data_sort == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $data_reg_id;
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $data_reg_id;
								}

							}else{
								$devices_select1[floor($i/1000)][] = $data_reg_id;
							}
						}

						$msg_subject = $ticker."[".$title1."]";
						if(!is_array($response_array_mb_id) || !in_array($data_mb_id, $response_array_mb_id)){
							insert_Notification_list("notice", '', '', $msg_subject, $address, $data_mb_id, '', '', '');
							array_push($response_array_mb_id, $data_mb_id);
						}
					}
					else
					{
						if($data_sort == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIs1[floor($i/1000)][] = $data_reg_id;
							}
							else
							{
								$newRegIdsIm1[floor($i/1000)][] = $data_reg_id;
							}

						}else{
							$devices_select1[floor($i/1000)][] = $data_reg_id;
						}
					}

				}

			}
		}
		else
		{
			$limit = 0 + (($data_array['m_page']-1)*$push_div_num);
			if($ca_id)
			{
				$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gpr_os_version from g5_gnupushapp_subscribe where gss_ca_name = '{$ca_id}' and gss_is_youngcart = 'Y' and gss_post_subscribe_onoff = 'Y'  order by gss_ix desc limit $limit, $push_div_num ";
				$devices_select_array = sql_query($query);
			}else{
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_youngcart = 'Y' and gpr_setting_youngcart_all = 'Y' order by gpr_ix desc limit $limit, $push_div_num ";
				$devices_select_array = sql_query($query);
			}

			for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
			{
				if($ca_id)
				{
					$data_other_setting = $rowddd['gss_other_setting'];
					$data_sort = $rowddd['gss_sort'];
					$data_mb_id = $rowddd['gss_mb_id'];
					$data_sync = $rowddd['gss_sync'];
					$data_reg_id = $rowddd['gss_reg_id'];
				}else{
					$data_other_setting = $rowddd['gpr_other_setting'];
					$data_sort = $rowddd['gpr_sort'];
					$data_mb_id = $rowddd['gpr_mb_id'];
					$data_sync = $rowddd['gpr_sync'];
					$data_reg_id = $rowddd['gpr_reg_id'];
				}
				if($data_sort == 'I'){
					$other_setting_array = unserialize($data_other_setting);
					$board_keyword = $other_setting_array[2];
					if(strlen($board_keyword) > 0)
					{
						if(strpos($board_keyword, ",") !== false)
						{
							$board_keyword_array = explode(',', $board_keyword);
							$keyword_exist = false;
							foreach($board_keyword_array as $value_keyword)
							{
								if(strpos($title1, $value_keyword) !== false || strpos($content, $value_keyword) !== false)
								{
									$keyword_exist = true;
								}
							}

							if(!$keyword_exist)
							{
								continue;
							}
						}
						else
						{
							if(!(strpos($title1, $board_keyword) !== false) && !(strpos($content, $board_keyword) !== false))
							{
								continue;
							}
						}

					}
				}

				if($data_sync != 'N' && $gnu_config["mypushlist"] == "Y")
				{
					if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
					{
						$count_badge = getBadge_by_mb_id($data_mb_id);
						$count_badge++;
						$stringBadge = "badgeN" . strval($count_badge);
						if($data_sort == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIbs1[$stringBadge][] = $data_reg_id;
							}
							else
							{
								$newRegIdsIbm1[$stringBadge][] = $data_reg_id;
							}

						}else{
							if($rowddd['gpr_os_version'] >= 8){
								$devices_newAndroid_select1[$stringBadge][] = $data_reg_id;
							}else{
								$devices_select1[floor($i/1000)][] = $data_reg_id;
							}
						}
					}
					else
					{
						if($data_sort == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIs1[floor($i/1000)][] = $data_reg_id;
							}
							else
							{
								$newRegIdsIm1[floor($i/1000)][] = $data_reg_id;
							}

						}else{
							$devices_select1[floor($i/1000)][] = $data_reg_id;
						}
					}

					$msg_subject = $ticker."[".$title1."]";
					if(!is_array($response_array_mb_id) || !in_array($data_mb_id, $response_array_mb_id)){
						insert_Notification_list("notice", '', '', $msg_subject, $address, $data_mb_id, '', '', '');
						array_push($response_array_mb_id, $data_mb_id);
					}
				}
				else
				{
					if($data_sort == 'I'){
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIs1[floor($i/1000)][] = $data_reg_id;
						}
						else
						{
							$newRegIdsIm1[floor($i/1000)][] = $data_reg_id;
						}

					}else{
						$devices_select1[floor($i/1000)][] = $data_reg_id;
					}
				}
			}
		}
		break;

	case "group" :

		$use_profile = "false";
		$profile_link = "none";
		$title1 = $data_array['title'];
		$content = $data_array['content'];
		$address = $data_array['address'];
		$level = $data_array['level'];
		$pushstyle = $data_array['pushstyle'];
		$image_src = $data_array['image_src'];
		$ticker = "새로운 공지사항이 있습니다.";
		$bottom_text = $address;
		$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

		$sql_setting = "";

		if($data_array['use_marketing'] == "Y"){
			$sql_setting = " and gpr_setting_marketing = 'Y' ";
		}else{
			$sql_setting = " and gpr_setting_notice = 'Y' ";
		}

		if($data_array['from'] == "quicksend")
		{
			if($data_array['sort'] != "must"){
				$sql_setting = "and gpr_setting_" . $data_array['sort'] . " = 'Y' ";
			}else{
				$sql_setting = "";
			}
			$etc1 = $data_array['etc'];
		}

		$banner = $data_array['effect'];

		if($data_array['m_page'] == 0)
		{
			$count_this = 0;
			if($level == "all")
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid WHERE 1 $sql_setting ";
				$rowddd = sql_fetch($query);
				$count_this = $rowddd['cnt'];
			}
			elseif($level == "oldversion")
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid WHERE 1 $sql_setting and gpr_version <> '{$gnu_config['webview_version']}'";
				$rowddd = sql_fetch($query);
				$count_this = $rowddd['cnt'];
			}
			elseif($level == "1")
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid WHERE 1 $sql_setting and gpr_sync = 'N' ";
				$rowddd = sql_fetch($query);
				$count_this = $rowddd['cnt'];
			}
			elseif(is_numeric($level))
			{
				$sql_setting2 = str_replace('gpr_', 'a.gpr_', $sql_setting);
				$level = intval($level);
				$sql_ex = "b.mb_level >= $level ";
				$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid a join {$g5['member_table']} b on a.gpr_mb_id = b.mb_id and $sql_ex where 1 {$sql_setting2} ";
				$rowddd = sql_fetch($query);
				$count_this = $rowddd['cnt'];
			}
			else
			{

				$final_reg_id = array();
				$level_group = str_replace('GNU_group_', '', $level);
				$res_this = sql_query("select mb_id from {$g5['group_member_table']} where gr_id = '$level_group'");
				for ($i=0; $rowddd=sql_fetch_array($res_this); $i++)
				{
					$res_device = get_device_by_member_id($rowddd['mb_id']);
					for ($j=0; $rowddd2=sql_fetch_array($res_device); $j++)
					{
						array_push($final_reg_id, $rowddd2['gpr_reg_id']);
					}
				}
				$count_this = count($final_reg_id);
			}

			if($count_this > $push_div_num)
			{
				$count_p = ceil($count_this / $push_div_num);

				for($i=0;$i<$count_p;$i++)
				{
					$data_array['m_page'] = $i+1;

					$page_num = $i+1;
					$gp_target_url = $address;
					$gp_target_title = $title1;					
					$gp_target_title = $gp_target_title."[".$page_num."/".$count_p."]";

					gnu_send_socket($data_array, '그룹별푸시알림', 'group', $gp_target_url, $gp_target_title);
				}

			}
			else
			{

				if($level == "all")
				{
					$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid WHERE 1 $sql_setting ";
				}
				elseif($level == "oldversion")
				{
					$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid WHERE 1 $sql_setting and gpr_version <> '{$gnu_config['webview_version']}'";
				}
				elseif($level == "1")
				{
					$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid WHERE 1 $sql_setting and gpr_sync = 'N' ";
				}
				elseif(is_numeric($level))
				{
					$sql_setting2 = str_replace('gpr_', 'a.gpr_', $sql_setting);
					$level = intval($level);
					$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid a join {$g5['member_table']} b on a.gpr_mb_id = b.mb_id and b.mb_level = {$level} where 1 $sql_setting2 ";
				}
				else
				{
					$imp_reg_id = implode(",", $final_reg_id);

					$include_mb_id= "";
					$include_mb_id = get_mb_id_listq($include_mb_id, $imp_reg_id);

					$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where 1 $sql_setting and gpr_reg_id in ({$include_mb_id}) ";
				}

				$devices_select = sql_query($query);

				$member_wrad = get_member($config['cf_admin']);

				for ($i=1; $rowddd=sql_fetch_array($devices_select); $i++)
				{
					if($rowddd['gpr_sort'] == 'I'){
						$other_setting_array = unserialize($rowddd['gpr_other_setting']);
					}

					if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
					{
						if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
						{
							$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
							$count_badge++;
							$stringBadge = "badgeN" . strval($count_badge);
							if($rowddd['gpr_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
								}

							}else{
								if($rowddd['gpr_os_version'] >= 8){
									$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
						else
						{
							if($rowddd['gpr_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
						}
						if(!is_array($response_array_mb_id) || !in_array($rowddd['gpr_mb_id'], $response_array_mb_id)){
							insert_Notification_list("notice", '', '', $title1, $address, $rowddd['gpr_mb_id'], $config['cf_admin'], $member_wrad['mb_nick'], '');
							array_push($response_array_mb_id, $rowddd['gpr_mb_id']);
						}
					}
					else
					{
						if($rowddd['gpr_sort'] == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}

						}else{
							$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}
					}
				}
			}
		}
		else
		{
			$m_page = $data_array['m_page'];

			$limit = 0 + (($m_page-1)*$push_div_num);
			$sql_limit = "order by gpr_ix desc limit $limit, $push_div_num ";
			if($level == "all")
			{
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid WHERE 1 $sql_setting $sql_limit";
			}
			elseif($level == "oldversion")
			{
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid WHERE 1 $sql_setting and gpr_version <> '{$gnu_config['webview_version']}' $sql_limit";
			}
			elseif(is_numeric($level))
			{
				$sql_setting2 = str_replace('gpr_', 'a.gpr_', $sql_setting);
				$level = intval($level);
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid a join {$g5['member_table']} b on a.gpr_mb_id = b.mb_id and b.mb_level = {$level} where 1 $sql_setting2 $sql_limit ";
			}
			else
			{
				$final_reg_id = array();
				$level_group = str_replace('GNU_group_', '', $level);
				$res_this = sql_query("select mb_id from {$g5['group_member_table']} where gr_id = '$level_group'");
				for ($i=0; $rowddd=sql_fetch_array($res_this); $i++)
				{
					$res_device = get_device_by_member_id($rowddd['mb_id']);
					for ($j=0; $rowddd2=sql_fetch_array($res_device); $j++)
					{
						array_push($final_reg_id, $rowddd2['gpr_reg_id']);
					}
				}
				$imp_reg_id = implode(",", $final_reg_id);

				$include_mb_id= "";
				$include_mb_id = get_mb_id_listq($include_mb_id, $imp_reg_id);

				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where 1 $sql_setting and gpr_reg_id in ({$include_mb_id}) ";
			}

			$devices_select = sql_query($query);

			$member_wrad = get_member($config['cf_admin']);

			for ($i=1; $rowddd=sql_fetch_array($devices_select); $i++)
			{
				if($rowddd['gpr_sort'] == 'I'){
					$other_setting_array = unserialize($rowddd['gpr_other_setting']);
				}

				if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
				{
					if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
					{
						$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
						$count_badge++;
						$stringBadge = "badgeN" . strval($count_badge);
						if($rowddd['gpr_sort'] == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
							}

						}else{
							if($rowddd['gpr_os_version'] >= 8){
								$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
						}
					}
					else
					{
						if($rowddd['gpr_sort'] == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}

						}else{
							$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}
					}

					if(!is_array($response_array_mb_id) || !in_array($rowddd['gpr_mb_id'], $response_array_mb_id)){
							insert_Notification_list("notice", '', '', $title1, $address, $rowddd['gpr_mb_id'], $config['cf_admin'], $member_wrad['mb_nick'], '');
							array_push($response_array_mb_id, $rowddd['gpr_mb_id']);
					}
				}
				else
				{
					if($rowddd['gpr_sort'] == 'I'){
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}
						else
						{
							$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}

					}else{
						$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
					}
				}
			}
		}
		break;

	case "delete" :

		if($data_array['m_page'] == 0)
		{

			$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid";
			$rowddd = sql_fetch($query);

			$devices_num = $rowddd['cnt'];

			if($devices_num > $push_div_num)
			{
				$count_p = ceil($devices_num / $push_div_num);

				for($i=0;$i<$count_p;$i++)
				{
					$data_array['m_page'] = $i+1;
					$page_num = $i+1;
					$gp_target_url = "#";
					$gp_target_title = "등록기기정리";
					$gp_target_title = $gp_target_title."[".$page_num."/".$count_p."]";
					gnu_send_socket($data_array, 'delete', 'delete', $gp_target_url, $gp_target_title);
				}

			}
			else
			{
				$etc1 = "none";
				$title1 = "none";
				$content = "none";
				$address = "none";
				$query = "select gpr_reg_id,gpr_sort from g5_gnupushapp_gcmregid";
				$devices_select = sql_query($query);

				for ($i=1; $rowddd=sql_fetch_array($devices_select); $i++)
				{
					if($rowddd['gpr_sort'] == 'I'){
						$devices_selectIphone[floor($i/1000)][] = $rowddd['gpr_reg_id'];
					}else{
						$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
					}
				}
			}
		}
		else
		{
			$m_page = $data_array['m_page'];

			$limit = 0 + (($m_page-1)*$push_div_num);
			$sql_limit = "order by gpr_ix desc limit $limit, $push_div_num ";
			$etc1 = "none";
			$title1 = "none";
			$content = "none";
			$address = "none";
			$query = "select gpr_reg_id,gpr_sort from g5_gnupushapp_gcmregid $sql_limit ";
			$devices_select = sql_query($query);

			for ($i=1; $rowddd=sql_fetch_array($devices_select); $i++)
			{
				if($rowddd['gpr_sort'] == 'I'){
					$devices_selectIphone[floor($i/1000)][] = $rowddd['gpr_reg_id'];
				}else{
					$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
				}
			}
		}

		break;

	case "new_coupon" :

		$use_profile = "false";
		$profile_link = "none";
		$pushstyle = "normal";
		if($gnu_config['push_style'] == "Y")
		{
			$pushstyle = "big_text";
		}
		$image_src = "none";
		$title1 = $ticker = $data_array['p_subject'];
		$content = stripslashes($data_array['p_contents']);
		$bottom_text = $gnu_config['youngcart_name'] . " 쿠폰 지급";
		$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

		$address = G5_SHOP_URL."/mypage.php";

		if($data_array['m_page'] == 0)
		{
			$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid a join {$g5['member_table']} b on a.gpr_mb_id = b.mb_id and b.mb_leave_date = '' and b.mb_intercept_date = '' and b.mb_id <> '{$data_array['cf_admin']}' where a.gpr_setting_youngcart = 'Y' ";

			$row = sql_fetch($query);
			$devices_num = $row['cnt'];

			if($devices_num > 0)
			{
				if($devices_num > $push_div_num)
				{
					$count_p = ceil($devices_num / $push_div_num);

					for($i=0;$i<$count_p;$i++)
					{
						$data_array['m_page'] = $i+1;

						$page_num = $i+1;
						$gp_target_url = G5_URL.'/adm/shop_admin/couponlist.php';
						$gp_target_title = $data_array['p_contents']."[".$page_num."/".$count_p."]";

						gnu_send_socket($data_array, 'Coupon', 'new_coupon', $gp_target_url, $gp_target_title);
					}

				}
				else
				{
					$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_os_version from g5_gnupushapp_gcmregid a join {$g5['member_table']} b on a.gpr_mb_id = b.mb_id and b.mb_leave_date = '' and b.mb_intercept_date = '' and b.mb_id <> '{$data_array['cf_admin']}' where a.gpr_setting_youngcart = 'Y' ";
					$devices_info = sql_query($query);

					for ($i=1; $rowddd=sql_fetch_array($devices_info); $i++)
					{
						if($rowddd['gpr_sort'] == 'I'){
							$other_setting_array = unserialize($rowddd['gpr_other_setting']);
						}

						if($rowddd['gpr_mb_id'] && $gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
						else
						{
							if($rowddd['gpr_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
						}
					}
				}

			}
		}else{

			$limit = 0 + (($data_array['m_page']-1)*$push_div_num);
			$sql_limit = "order by gpr_ix desc limit $limit, $push_div_num ";

			$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_os_version from g5_gnupushapp_gcmregid a join {$g5['member_table']} b on a.gpr_mb_id = b.mb_id and b.mb_leave_date = '' and b.mb_intercept_date = '' and b.mb_id <> '{$data_array['cf_admin']}' where a.gpr_setting_youngcart = 'Y' $sql_limit";
			$devices_info = sql_query($query);

			for ($i=1; $rowddd=sql_fetch_array($devices_info); $i++)
			{

				if($rowddd['gpr_sort'] == 'I'){
					$other_setting_array = unserialize($rowddd['gpr_other_setting']);
				}

				if($rowddd['gpr_mb_id'] && $gnu_config["mypushlist"] == "Y")
				{
					if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
					{
						$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
						$count_badge++;
						$stringBadge = "badgeN" . strval($count_badge);
						if($rowddd['gpr_sort'] == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
							}

						}else{
							if($rowddd['gpr_os_version'] >= 8){
								$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
						}
					}
					else
					{
						if($rowddd['gpr_sort'] == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
							else
							{
								$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}

						}else{
							$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}
					}
				}
				else
				{
					if($rowddd['gpr_sort'] == 'I'){
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}
						else
						{
							$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}

					}else{
						$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
					}
				}
			}
		}

		break;

	case "new_post" :

		$use_profile = "false";
		$profile_link = "none";
		
		if($mb_icon_url){
			$use_profile = "true";
			$profile_link = $mb_icon_url;
		}else{
			$member_wr = get_member($data_array['member_id']);
			if($member_wr['mb_7']){
				$use_profile = "true";
				$profile_link = $member_wr['mb_7'];
			}
		}
		if($gnu_config['profile_p'] == "N")
		{
			$use_profile = "false";
			$profile_link = "none";
		}

		$title = $data_array['wr_subject'];
		$title = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;', '&#034;'), array('<', '>', '"', ' ', '&','"'), stripslashes($title));

		$content = $data_array['wr_content'];
		$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;', '&#034;'), array('<', '>', '"', ' ', '&','"'), stripslashes($content));

		$address = G5_BBS_URL.'/board.php?bo_table='.$data_array['bo_table'].'&wr_id='.$data_array['wr_id'];

		$bo_table_p = $data_array['bo_table'];
		$board_config = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table_p' ");
		$gr_id_pu = $board_config['gr_id'];

		$pushstyle = "normal";
		$image_src = "none";
		
		if($gnu_config['push_style'] == "Y")
		{
			$pushstyle = "big_text";
			if($data_array['wr_file'] > 0){
				$pushstyle = "big_picture";
				if($data_array['thumb_src'] != 'none'){
					$image_src = $data_array['thumb_src'];
				}else{
					if($gnu_config['build_sort'] == 'A')
					{
						$tmp_write_table = $g5['write_prefix'] . $data_array['bo_table']; // 게시판 테이블 전체이름
						$sql_t = " select * from {$tmp_write_table} where wr_id = '{$data_array['wr_id']}' ";
						$result_row = sql_fetch($sql_t);
						$thumb = apms_wr_thumbnail($data_array['bo_table'], $result_row, 500, 250, false, true);
					}
					else
					{
						$thumb = get_list_thumbnail($data_array['bo_table'], $data_array['wr_id'], 500, 250);
					}
					
					if($thumb['src']) {
						$image_src = $thumb['src'];
					}
				}
			}
		}

		$func = "normal";

		if(is_array($gnu_config['notice_module_srls']) && in_array($data_array['bo_table'], $gnu_config['notice_module_srls']))
		{
			$func = "notice";
		}
		else if(is_array($gnu_config['only_admin_push_module_srls']) && in_array($data_array['bo_table'], $gnu_config['only_admin_push_module_srls']))
		{
			$func = "counsel";
		}
		else if(is_array($gnu_config['group_module_srls']) && in_array($data_array['bo_table'], $gnu_config['group_module_srls']))
		{
			$func = "group";
		}
		else
		{

			if($data_array['secret'] == 'secret')
			{
				$func = "secret";
			}

			if($data_array['wr_reply'])
			{
				$func = "reply";
			}

		}

		if($gnu_config['popup_push_style'] == "Y" && is_array($gnu_config['popup_module_srls']) && in_array($data_array['bo_table'], $gnu_config['popup_module_srls']))
		{
			$banner = "popup";
		}

		if($gnu_config['headsup_push_style'] == "Y" && is_array($gnu_config['headsup_module_srls']) && in_array($data_array['bo_table'], $gnu_config['headsup_module_srls']))
		{
			$banner = "headsup";
		}

		$setting_board_table = $data_array['bo_table'];
		$setting_ca_name = "";

		if($data_array['ca_name'] && is_array($gnu_config['category_module_srls']) && in_array($data_array['bo_table'], $gnu_config['category_module_srls']))
		{
			$setting_ca_name = $data_array['ca_name'];
		}

		if($data_array['m_page'] == 0)
		{
			$exclude_targets = array();
			array_push($exclude_targets, $data_array['member_id']);

			if($func == "normal")
			{
				$pushchannel = "normal";
				if($gnu_config['choose_board'] != "X" && ($gnu_config['choose_board'] != "N" || $gnu_config['choose_board_s'] != "N"))
				{

					$devices_num = get_subscribe_device_push('count', 'newpost', $setting_board_table, $setting_ca_name, $data_array['board_grant_c']);

					if($devices_num > $push_div_num)
					{
						$count_p = ceil($devices_num / $push_div_num);

						for($i=0;$i<$count_p;$i++)
						{
							$data_array['m_page'] = $i+1;

							$page_num = $i+1;

							$board_subject = $data_array['board_subject'];
							$gp_target_url = G5_BBS_URL.'/board.php?bo_table='.$data_array['bo_table'].'&wr_id='.$data_array['wr_id'];
							$gp_target_title = strip_tags(cut_str($data_array['wr_subject'],50,''));
							$gp_target_title = $gp_target_title."[".$page_num."/".$count_p."]";

							gnu_send_socket($data_array, $board_subject, 'new_post', $gp_target_url, $gp_target_title);
						}
					}
					else
					{

						$title1 = $title;
						$ticker = $data_array['board_subject']."에 새 글이 올라왔습니다.";
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
						$devices_select_array = get_subscribe_device_push('array','newpost', $setting_board_table, $setting_ca_name, $data_array['board_grant_c']);

						for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
						{
							if($rowddd['gss_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gss_other_setting']);
								$board_keyword = $other_setting_array[1];
								if(strlen($board_keyword) > 0)
								{
									if(strpos($board_keyword, ",") !== false)
									{
										$board_keyword_array = explode(',', $board_keyword);
										$keyword_exist = false;
										foreach($board_keyword_array as $value_keyword)
										{
											if(strpos($title1, $value_keyword) !== false || strpos($content, $value_keyword) !== false)
											{
												$keyword_exist = true;
											}
										}

										if(!$keyword_exist)
										{
											continue;
										}
									}
									else
									{
										if(!(strpos($title1, $board_keyword) !== false) && !(strpos($content, $board_keyword) !== false))
										{
											continue;
										}
									}

								}
							}

							if($rowddd['gss_sync'] != 'N' && is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
							{
								continue;
							}
							else
							{

								if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
								{
									if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
									{
										$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
										$count_badge++;
										$stringBadge = "badgeN" . strval($count_badge);
										if($rowddd['gss_sort'] == 'I'){
											if($other_setting_array[0] == 'sound')
											{
												$newRegIdsIbs1[$stringBadge][] = $rowddd['gss_reg_id'];
											}
											else
											{
												$newRegIdsIbm1[$stringBadge][] = $rowddd['gss_reg_id'];
											}

										}else{
											if($rowddd['gpr_os_version'] >= 8){
												$devices_newAndroid_select1[$stringBadge][] = $rowddd['gss_reg_id'];
											}else{
												$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
										}
									}
									else
									{
										if($rowddd['gss_sort'] == 'I'){
											if($other_setting_array[0] == 'sound')
											{
												$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
											else
											{
												$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}

										}else{
											$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
									}

									$msg_subject = $data_array['board_subject']."에 새 글이 올라왔습니다. [".$title."]";
									if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
										insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
										array_push($response_array_mb_id, $rowddd['gss_mb_id']);
									}
									
								}
								else
								{
									if($rowddd['gss_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
										else
										{
											$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}

									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
								}
							}
						}

					}
				}

			}
			elseif($func == "notice")
			{

				$setting = "notice";

				$devices_num = get_devices_push('count', $setting, $data_array['board_grant_c'],$data_array['bo_table']);

				if($devices_num > $push_div_num)
				{
					$count_p = ceil($devices_num / $push_div_num);

					for($i=0;$i<$count_p;$i++)
					{
						$data_array['m_page'] = $i+1;

						$page_num = $i+1;

						$board_subject = $data_array['board_subject'];
						$gp_target_url = G5_BBS_URL.'/board.php?bo_table='.$data_array['bo_table'].'&wr_id='.$data_array['wr_id'];
						$gp_target_title = strip_tags(cut_str($data_array['wr_subject'],50,''));
						$gp_target_title = $gp_target_title."[".$page_num."/".$count_p."]";

						gnu_send_socket($data_array, $board_subject, 'new_post', $gp_target_url, $gp_target_title);
					}
					

				}
				else
				{
					$title1 = "[공지] ".$title;
					$ticker = $data_array['board_subject']."에 공지글이 올라왔습니다.";
					$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
					$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
					$devices_select_array = get_devices_push('array', $setting, $data_array['board_grant_c'],$data_array['bo_table']);

					for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
					{
						if($rowddd['gpr_sort'] == 'I'){
							$other_setting_array = unserialize($rowddd['gpr_other_setting']);
						}

						if(is_array($exclude_targets) && in_array($rowddd['gpr_mb_id'], $exclude_targets))
						{
							continue;
						}
						else
						{
							if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}else{
											$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}

									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}

								$msg_subject = $data_array['board_subject']."에 공지사항이 올라왔습니다. [".$title."]";
								if(!is_array($response_array_mb_id) || !in_array($rowddd['gpr_mb_id'], $response_array_mb_id)){
									insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gpr_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
									array_push($response_array_mb_id, $rowddd['gpr_mb_id']);
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
					}
				}

			}
			elseif($func == "secret")
			{

				//비밀글의 경우 알림설정된 관리자에게만 보냄
				$title1 = "[비밀글] ".$title;
				$ticker = $data_array['board_subject']."에 비밀글이 올라왔습니다.";
				$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
				$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

				$setting = "newpost";

				$include_mb_id = "";

				if($board_config['bo_admin'])
				{
					$include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
				}

				if($gr_id_pu)
				{
					$group_config = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id_pu' ");
					if($group_config['gr_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $group_config['gr_admin']);
				}

				if($gnu_config['build_sort'] == 'A')
				{
					if($config['as_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $config['as_admin']);
				}

				$devices_admin = get_subscribe_device_push('array', $setting, $setting_board_table, $setting_ca_name, 10, $func, $include_mb_id);
				for ($i=1; $rowddd=sql_fetch_array($devices_admin); $i++)
				{
					if($rowddd['gss_sort'] == 'I'){
						$other_setting_array = unserialize($rowddd['gss_other_setting']);
					}
					if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
					{
						continue;
					}
					else
					{
						if($gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs1[$stringBadge][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIbm1[$stringBadge][] = $rowddd['gss_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select1[$stringBadge][] = $rowddd['gss_reg_id'];
									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
							}

							$msg_subject = $ticker." [".$title."]";
							if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
								insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
								array_push($response_array_mb_id, $rowddd['gss_mb_id']);
							}
						}
						else
						{
							if($rowddd['gss_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
							}
						}
					}
				}
			}
			elseif($func == "group")
			{
				//접근 권한 회원들에게 푸시알림

				// mb_id array로 다 추가시킨 후, array foreach 시키면서 regid array값을 가져옴. -> regid,구독여부 등으로 subscribe조사뒤에 푸시처리하면 됨.

				$final_reg_id = array();
				$res_this = sql_query("select mb_id from {$g5['group_member_table']} where gr_id = '$gr_id_pu'");
				for ($i=0; $rowddd=sql_fetch_array($res_this); $i++)
				{
					$res_device = get_device_by_member_id($rowddd['mb_id']);
					for ($j=0; $rowddd2=sql_fetch_array($res_device); $j++)
					{
						array_push($final_reg_id, $rowddd2['gpr_reg_id']);
					}
				}

				$array_mb_id = array();
				if($board_config['bo_admin'])
				{
					$array_m_list = explode(",",$board_config['bo_admin']);
					foreach($array_m_list as $bo_admin_mb)
					{
						array_push($array_mb_id, $bo_admin_mb);
					}
				}

				$group_config = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id_pu' ");
				if($group_config['gr_admin'])
				{
					$array_m_list = explode(",",$group_config['gr_admin']);
					foreach($array_m_list as $bo_admin_mb)
					{
						array_push($array_mb_id, $bo_admin_mb);
					}
				}
				
				if($gnu_config['build_sort'] == 'A')
				{
					if($config['as_admin'])
					{
						$array_m_list = explode(",",$config['as_admin']);
						foreach($array_m_list as $bo_admin_mb)
						{
							array_push($array_mb_id, $bo_admin_mb);
						}
					}
				}

				$admin_result = sql_query("select mb_id from {$g5['member_table']} where mb_level = 10");
				for ($i=0; $admin_mb_idss=sql_fetch_array($admin_result); $i++)
				{
					array_push($array_mb_id, $admin_mb_idss['mb_id']);
				}
				
				foreach($array_mb_id as $mb_id_val)
				{
					$res_device = get_device_by_member_id($mb_id_val);
					for ($j=0; $rowddd2=sql_fetch_array($res_device); $j++)
					{
						if(!is_array($final_reg_id) || !in_array($rowddd2['gpr_reg_id'], $final_reg_id))
						{
							array_push($final_reg_id, $rowddd2['gpr_reg_id']);
						}
					}
				}

				$imp_reg_id = implode(",", $final_reg_id);

				$include_mb_id= "";
				$include_mb_id = get_mb_id_listq($include_mb_id, $imp_reg_id);

				$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where gss_is_youngcart = 'N' and gss_post_subscribe_onoff = 'Y' and gss_bo_table = '{$data_array['bo_table']}' and gss_post_subscribe = 'Y' and gss_reg_id in ({$include_mb_id}) ";
				$devices_selected = sql_query($query);

				$title1 = $title;
				$ticker = $data_array['board_subject']."에 새글이 올라왔습니다.";
				$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
				$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

				for ($i=1; $rowddd=sql_fetch_array($devices_selected); $i++)
				{

					if($rowddd['gss_sort'] == 'I'){
						$other_setting_array = unserialize($rowddd['gss_other_setting']);
					}

					if(is_array($exclude_targets) && in_array($rowddd['gss_reg_id'], $exclude_targets))
					{
						continue;
					}
					else
					{

						if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs1[$stringBadge][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIbm1[$stringBadge][] = $rowddd['gss_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select1[$stringBadge][] = $rowddd['gss_reg_id'];
									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
							}

							$msg_subject = $ticker." [".$title."]";
							if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
								insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
								array_push($response_array_mb_id, $rowddd['gss_mb_id']);
							}
						}
						else
						{
							if($rowddd['gss_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
							}
						}
					}
				}


			}
			elseif($func == "counsel")
			{
				//상담게시판은 오직 관리자에게 알림설정 여부와 관계없이 무조건 알림
				$setting = "";

				$title1 = $title;
				$ticker = $data_array['board_subject']."에 새글이 올라왔습니다.";
				$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
				$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

				$include_mb_id = "";

				if($board_config['bo_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
				if($gr_id_pu)
				{
					$group_config = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id_pu' ");
					if($group_config['gr_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $group_config['gr_admin']);
				}
				
				if($gnu_config['build_sort'] == 'A')
				{
					if($config['as_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $config['as_admin']);
					
				}

				$devices_admin = get_devices_push('array', $setting, 10, $data_array['bo_table'], $func, $include_mb_id);
				for ($i=1; $rowddd=sql_fetch_array($devices_admin); $i++)
				{
					if($rowddd['gpr_sort'] == 'I'){
						$other_setting_array = unserialize($rowddd['gpr_other_setting']);
					}

					if(is_array($exclude_targets) && in_array($rowddd['gpr_mb_id'], $exclude_targets))
					{
						continue;
					}
					else
					{

						if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}

							$msg_subject = $ticker." [".$title."]";
							if(!is_array($response_array_mb_id) || !in_array($rowddd['gpr_mb_id'], $response_array_mb_id)){
								insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gpr_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
								array_push($response_array_mb_id, $rowddd['gpr_mb_id']);
							}
						}
						else
						{
							if($rowddd['gpr_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
						}
					}
				}


			}
			elseif($func == "reply")
			{

				//부모글 mb_id 가져오기
				$str_length = strlen($data_array['wr_reply']);
				
				if($str_length >= 2)
				{
					$wr_reply_p = substr($data_array['wr_reply'], 0, -1);
					$sql_p = "select mb_id from {$data_array['write_table']} where wr_num = {$data_array['wr_num']} and wr_reply = '{$wr_reply_p}' ";
					$row_p = sql_fetch($sql_p);
					$p_mb_id = $row_p['mb_id'];
					$row_mem = get_member($p_mb_id);
					$p_mb_nick = $row_mem['mb_nick'];
				}
				else
				{
					$sql_p = "select * from {$data_array['write_table']} where wr_num = {$data_array['wr_num']} ";
					$wr_p = sql_query($sql_p);
					for ($i=0; $row_p=sql_fetch_array($wr_p); $i++)
					{
						if($row_p['wr_reply'] == "")
						{
							$p_mb_id = $row_p['mb_id'];
							$row_mem = get_member($p_mb_id);
							$p_mb_nick = $row_mem['mb_nick'];
						}
					}
				}

				//비밀글일 경우에는 알림설정된 관리자와 부모글에만 푸시 알림
				if($data_array['secret'] == 'secret')
				{

					//부모글에게 먼저 보냄
					if($p_mb_id && $p_mb_id != $data_array['member_id'])
					{
						$title1 = "[답변] ".$title;
						$ticker = $data_array['wr_name'] . "님이 회원님의 글에 비밀답변을 달았습니다.";
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
						array_push($exclude_targets, $p_mb_id);

						$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_myreply = 'Y' and gpr_mb_id = '{$p_mb_id}' ";
						$devices_a = sql_query($sql);
						for ($i=1; $rowddd=sql_fetch_array($devices_a); $i++)
						{
							if($rowddd['gpr_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gpr_other_setting']);
							}

							if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}else{
											$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}

									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
					}


					// 관리자에게 보냄 
					$title2 = "[답변] ".$title;
					$ticker = $data_array['wr_name'] . "님이 ".$p_mb_nick."님의 글에 비밀답변을 달았습니다.";
					$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
					$etc2 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

					$setting = "newpost";
					$include_mb_id = "";

					if($board_config['bo_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
					if($gr_id_pu)
					{
						$group_config = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id_pu' ");
						if($group_config['gr_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $group_config['gr_admin']);
					}
					
					if($gnu_config['build_sort'] == 'A')
					{
						if($config['as_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $config['as_admin']);
					}
					$devices_admin = get_subscribe_device_push('array', 'newpost', $setting_board_table, $setting_ca_name, 10, "none", $include_mb_id);
					for ($i=1; $rowddd=sql_fetch_array($devices_admin); $i++)
					{
						if($rowddd['gss_sort'] == 'I'){
							$other_setting_array = unserialize($rowddd['gss_other_setting']);
						}
						if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
						{
							continue;
						}
						else
						{
							if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gss_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs2[$stringBadge][] = $rowddd['gss_reg_id'];
										}
										else
										{
											$newRegIdsIbm2[$stringBadge][] = $rowddd['gss_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select2[$stringBadge][] = $rowddd['gss_reg_id'];
										}else{
											$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gss_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs2[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
										else
										{
											$newRegIdsIm2[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}

									}else{
										$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
								}

								$msg_subject = $ticker." [".$title."]";
								if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
									insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
									array_push($response_array_mb_id, $rowddd['gss_mb_id']);
								}
							}
							else
							{
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs2[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIm2[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}

								}else{
									$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
							}
						}
					}
					
				}
				else
				{

					//부모글에 보냄
					if($p_mb_id && $p_mb_id != $data_array['member_id'])
					{
						$title1 = "[답변] ".$title;
						$ticker = $data_array['wr_name'] . "님이 회원님의 글에 답변을 달았습니다.";
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
						array_push($exclude_targets, $p_mb_id);

						$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_myreply = 'Y' and gpr_mb_id = '{$p_mb_id}' ";
						$devices_a = sql_query($sql);
						for ($i=1; $rowddd=sql_fetch_array($devices_a); $i++)
						{
							if($rowddd['gpr_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gpr_other_setting']);
							}

							if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}else{
											$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}

									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
					}

					if($gnu_config['choose_board'] != "X" && ($gnu_config['choose_board'] != "N" || $gnu_config['choose_board_s'] != "N"))
					{

						$devices_num = get_subscribe_device_push('count', 'newpost', $setting_board_table, $setting_ca_name, $data_array['board_grant_c']);

						if($devices_num > $push_div_num)
						{
							$count_p = ceil($devices_num / $push_div_num);

							for($i=0;$i<$count_p;$i++)
							{
								$data_array['m_page'] = $i+1;

								$page_num = $i+1;

								$board_subject = $data_array['board_subject'];
								$gp_target_url = G5_BBS_URL.'/board.php?bo_table='.$data_array['bo_table'].'&wr_id='.$data_array['wr_id'];
								$gp_target_title = strip_tags(cut_str($data_array['wr_subject'],50,''));
								$gp_target_title = $gp_target_title."[".$page_num."/".$count_p."]";

								gnu_send_socket($data_array, $board_subject, 'new_post', $gp_target_url, $gp_target_title);
							}

						}
						else
						{
							$pushchannel2 = "normal";
							$title2 = "[답변] ".$title;
							$ticker = $data_array['wr_name'] . "님이 ".$p_mb_nick."님의 글에 답변을 달았습니다.";
							$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
							$etc2 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

							$devices_reply = get_subscribe_device_push('array', 'newpost', $setting_board_table, $setting_ca_name, $data_array['board_grant_c']);
							for ($i=1; $rowddd=sql_fetch_array($devices_reply); $i++)
							{
								if($rowddd['gss_sort'] == 'I'){
									$other_setting_array = unserialize($rowddd['gss_other_setting']);
									$board_keyword = $other_setting_array[1];
									if(strlen($board_keyword) > 0)
									{
										if(strpos($board_keyword, ",") !== false)
										{
											$board_keyword_array = explode(',', $board_keyword);
											$keyword_exist = false;
											foreach($board_keyword_array as $value_keyword)
											{
												if(strpos($title, $value_keyword) !== false || strpos($content, $value_keyword) !== false)
												{
													$keyword_exist = true;
												}
											}

											if(!$keyword_exist)
											{
												continue;
											}
										}
										else
										{
											if(!(strpos($title1, $board_keyword) !== false) && !(strpos($content, $board_keyword) !== false))
											{
												continue;
											}
										}

									}
								}
								if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
								{
									continue;
								}
								else
								{
									if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
									{
										if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
										{
											$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
											$count_badge++;
											$stringBadge = "badgeN" . strval($count_badge);
											if($rowddd['gss_sort'] == 'I'){
												if($other_setting_array[0] == 'sound')
												{
													$newRegIdsIbs2[$stringBadge][] = $rowddd['gss_reg_id'];
												}
												else
												{
													$newRegIdsIbm2[$stringBadge][] = $rowddd['gss_reg_id'];
												}

											}else{
												if($rowddd['gpr_os_version'] >= 8){
													$devices_newAndroid_select2[$stringBadge][] = $rowddd['gss_reg_id'];
												}else{
													$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}
											}
										}
										else
										{
											if($rowddd['gss_sort'] == 'I'){
												if($other_setting_array[0] == 'sound')
												{
													$newRegIdsIs2[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}
												else
												{
													$newRegIdsIm2[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}

											}else{
												$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
										}

										$msg_subject = $ticker." [".$title."]";
										if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
											insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
											array_push($response_array_mb_id, $rowddd['gss_mb_id']);
										}
									}
									else
									{
										if($rowddd['gss_sort'] == 'I'){
											if($other_setting_array[0] == 'sound')
											{
												$newRegIdsIs2[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
											else
											{
												$newRegIdsIm2[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}

										}else{
											$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
									}
								}
							}
						}
					}
				}
			}

		}
		else
		{
			$exclude_targets = array();
			array_push($exclude_targets, $data_array['member_id']);

			$pushchannel = "normal";
			
			if($func == "normal")
			{
				$title1 = $title;
				$ticker = $data_array['board_subject']."에 새 글이 올라왔습니다.";
				$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
				$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

				$devices_select_array = get_subscribe_device_push_page('newpost', $setting_board_table, $setting_ca_name, $data_array['board_grant_c'], $data_array['m_page'], $push_div_num);

				for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
				{
					if($rowddd['gss_sort'] == 'I'){
						$other_setting_array = unserialize($rowddd['gss_other_setting']);
						$board_keyword = $other_setting_array[1];
						if(strlen($board_keyword) > 0)
						{
							if(strpos($board_keyword, ",") !== false)
							{
								$board_keyword_array = explode(',', $board_keyword);
								$keyword_exist = false;
								foreach($board_keyword_array as $value_keyword)
								{
									if(strpos($title, $value_keyword) !== false || strpos($content, $value_keyword) !== false)
									{
										$keyword_exist = true;
									}
								}

								if(!$keyword_exist)
								{
									continue;
								}
							}
							else
							{
								if(!(strpos($title, $board_keyword) !== false) && !(strpos($content, $board_keyword) !== false))
								{
									continue;
								}
							}

						}
					}

					if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
					{
						continue;
					}
					else
					{

						if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs1[$stringBadge][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIbm1[$stringBadge][] = $rowddd['gss_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select1[$stringBadge][] = $rowddd['gss_reg_id'];
									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
							}

							$msg_subject = $ticker." [".$title."]";
							if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
								insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
								array_push($response_array_mb_id, $rowddd['gss_mb_id']);
							}
						}
						else
						{
							if($rowddd['gss_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
							}
						}
					}
				}

			}
			elseif($func == "notice")
			{

				$title1 = "[공지] ".$title;
				$ticker = $data_array['board_subject']."에 공지글이 올라왔습니다.";
				$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
				$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

				$devices_select_array = get_devices_push_page('notice', $data_array['board_grant_c'], $data_array['bo_table'], $data_array['m_page'], $push_div_num);

				for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
				{
					if($rowddd['gpr_sort'] == 'I'){
						$other_setting_array = unserialize($rowddd['gpr_other_setting']);
					}

					if(is_array($exclude_targets) && in_array($rowddd['gpr_mb_id'], $exclude_targets))
					{
						continue;
					}
					else
					{
						if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}

							$msg_subject = $ticker." [".$title."]";
							if(!is_array($response_array_mb_id) || !in_array($rowddd['gpr_mb_id'], $response_array_mb_id)){
								insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gpr_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
								array_push($response_array_mb_id, $rowddd['gpr_mb_id']);
							}
						}
						else
						{
							if($rowddd['gpr_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
						}
					}
				}

			}
			elseif($func == "reply")
			{
				$pushchannel2 = "normal";

				$title2 = "[답변] ".$title;
				$ticker = $data_array['wr_name'] . "님이 ".$p_mb_nick."님의 글에 답변을 달았습니다.";
				$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
				$etc2 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

				$devices_a = get_subscribe_device_push_page('newpost', $setting_board_table, $setting_ca_name, $data_array['board_grant_c'], $data_array['m_page'], $push_div_num);

				for ($i=1; $rowddd=sql_fetch_array($devices_a); $i++)
				{
					if($rowddd['gss_sort'] == 'I'){
						$other_setting_array = unserialize($rowddd['gss_other_setting']);
						$board_keyword = $other_setting_array[1];
						if(strlen($board_keyword) > 0)
						{
							if(strpos($board_keyword, ",") !== false)
							{
								$board_keyword_array = explode(',', $board_keyword);
								$keyword_exist = false;
								foreach($board_keyword_array as $value_keyword)
								{
									if(strpos($title, $value_keyword) !== false || strpos($content, $value_keyword) !== false)
									{
										$keyword_exist = true;
									}
								}

								if(!$keyword_exist)
								{
									continue;
								}
							}
							else
							{
								if(!(strpos($title, $board_keyword) !== false) && !(strpos($content, $board_keyword) !== false))
								{
									continue;
								}
							}

						}
					}

					if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
					{
						continue;
					}
					else
					{

						if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs2[$stringBadge][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIbm2[$stringBadge][] = $rowddd['gss_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select2[$stringBadge][] = $rowddd['gss_reg_id'];
									}else{
										$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gss_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs2[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
									else
									{
										$newRegIdsIm2[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}

								}else{
									$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
							}

							$msg_subject = $ticker." [".$title."]";
							if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
								insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_id'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], '');
								array_push($response_array_mb_id, $rowddd['gss_mb_id']);
							}
						}
						else
						{
							if($rowddd['gss_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs2[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
								else
								{
									$newRegIdsIm2[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}

							}else{
								$devices_select2[floor($i/1000)][] = $rowddd['gss_reg_id'];
							}
						}
					}
				}
			}

		}

		break;

	case "new_comment" :

		$use_profile = "false";
		$profile_link = "none";
		$mb_icon_url = get_gnu_profile_image($data_array['member_id']);
		if($mb_icon_url){
			$use_profile = "true";
			$profile_link = $mb_icon_url;
		}else{
			$member_wr = get_member($data_array['member_id']);
			if($member_wr['mb_7']){
				$use_profile = "true";
				$profile_link = $member_wr['mb_7'];
			}

		}

		if($gnu_config['profile_p'] == "N")
		{
			$use_profile = "false";
			$profile_link = "none";
		}

		$bo_table_p = $data_array['bo_table'];
		$board_config = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table_p' ");
		$gr_id_pu = $board_config['gr_id'];

		$title = $data_array['wr_name'] . "님이 ".$data_array['board_subject'] . "에 새 댓글을 작성하였습니다.";
		$content = $data_array['wr_content'];
		$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), stripslashes($content));
		$address = G5_BBS_URL.'/board.php?bo_table='.$data_array['bo_table'].'&wr_id='.$data_array['wr_parent'].'#c_'.$data_array['comment_id'];

		$pushstyle = "normal";
		$image_src = "none";
		
		if($gnu_config['push_style'] == "Y")
		{
			$pushstyle = "big_text";
		}

		$func = "normal";

		//대댓글일 경우

		if($data_array['comment_reply'])
		{
			$func = "comment_reply";
		}

		if(is_array($gnu_config['only_admin_push_module_srls']) && in_array($data_array['bo_table'], $gnu_config['only_admin_push_module_srls']))
		{
			$func = "counsel";
		}

		if($gnu_config['popup_push_style'] == "Y" && is_array($gnu_config['popup_module_srls']) && in_array($data_array['bo_table'], $gnu_config['popup_module_srls']))
		{
			$banner = "popup";
		}

		if($gnu_config['headsup_push_style'] == "Y" && is_array($gnu_config['headsup_module_srls']) && in_array($data_array['bo_table'], $gnu_config['headsup_module_srls']))
		{
			$banner = "headsup";
		}

		$setting_board_table = $data_array['bo_table'];
		$setting_ca_name = "";

		if($data_array['ca_name'] && is_array($gnu_config['category_module_srls']) && in_array($data_array['bo_table'], $gnu_config['category_module_srls']))
		{
			$setting_ca_name = $data_array['ca_name'];
		}

		if($data_array['m_page'] == 0)
		{
			$exclude_targets = array();
			array_push($exclude_targets, $data_array['member_id']);

			//해당 글 작성자 정보 가져오기
			$sql_p = "select mb_id,wr_option from {$data_array['write_table']} where wr_id = {$data_array['wr_parent']} ";
			$row_p = sql_fetch($sql_p);
			$p_mb_id = $row_p['mb_id'];

			if($func == "normal")
			{
				//비밀댓글일 경우 - 알림설정된 해당 글 작성자에게만 푸시 알림
				if($data_array['secret'] == 'secret')
				{
					if($p_mb_id && $p_mb_id != $data_array['member_id'])
					{
						$title1 = $data_array['wr_name'] . "님이 회원님의 글에 비밀 댓글을 작성하였습니다.";
						$ticker = $title1;
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
						array_push($exclude_targets, $p_mb_id);

						$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_mypost_com = 'Y' and gpr_mb_id = '{$p_mb_id}' ";
						$devices_a = sql_query($sql);
						for ($i=1; $rowddd=sql_fetch_array($devices_a); $i++)
						{
							if($rowddd['gpr_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gpr_other_setting']);
							}

							if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}else{
											$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}

									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
						
					}
				}
				else
				{
					//일반 댓글일 경우 - 해당 글 작성자 & 꼬리글 작성자 & 알림설정된 모든 기기에 푸시 알림

					//먼저 해당 글 작성자에게 푸시 알림
					if($p_mb_id && $p_mb_id != $data_array['member_id'])
					{
						$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_mypost_com = 'Y' and gpr_mb_id = '{$p_mb_id}' ";
						$devices_parent = sql_query($sql);
						for ($i=1; $rowddd=sql_fetch_array($devices_parent); $i++)
						{
							if($rowddd['gpr_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gpr_other_setting']);
							}

							if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}else{
											$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}

									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
						$title1 = $data_array['wr_name'] . "님이 회원님의 글에 새 댓글을 작성하였습니다.";
						$ticker = $title1;
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
						array_push($exclude_targets, $p_mb_id);
					}

					//해당글이 비밀글일 경우에는 알림설정된 관리자에게만 푸시 알림 함
					if(strpos($row_p['wr_option'], "secret") !== false)
					{
						$title3 = $title;
						$ticker = $title;
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc3 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

						$setting = "newcom";

						$include_mb_id = "";

						if($board_config['bo_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
						if($gr_id_pu)
						{
							$group_config = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id_pu' ");
							if($group_config['gr_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $group_config['gr_admin']);
						}
						
						if($gnu_config['build_sort'] == 'A')
						{
							if($config['as_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $config['as_admin']);
							
						}else{
							if(isset($board_config['bo_admin'])) $include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
						}

						$devices_admin = get_subscribe_device_push('array',$setting, $setting_board_table, $setting_ca_name, 10, "secret", $include_mb_id);

						for ($i=1; $rowddd=sql_fetch_array($devices_admin); $i++)
						{
							if($rowddd['gss_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gss_other_setting']);
							}

							if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
							{
								continue;
							}
							else
							{
								if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
								{
									if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
									{
										$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
										$count_badge++;
										$stringBadge = "badgeN" . strval($count_badge);
										if($rowddd['gss_sort'] == 'I'){
											if($other_setting_array[0] == 'sound')
											{
												$newRegIdsIbs3[$stringBadge][] = $rowddd['gss_reg_id'];
											}
											else
											{
												$newRegIdsIbm3[$stringBadge][] = $rowddd['gss_reg_id'];
											}

										}else{
											if($rowddd['gpr_os_version'] >= 8){
												$devices_newAndroid_select3[$stringBadge][] = $rowddd['gss_reg_id'];
											}else{
												$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
										}
									}
									else
									{
										if($rowddd['gss_sort'] == 'I'){
											if($other_setting_array[0] == 'sound')
											{
												$newRegIdsIs3[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
											else
											{
												$newRegIdsIm3[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}

										}else{
											$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
									}

									$msg_subject = $ticker." [".cut_str($content,40,'...')."]";
									if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
										insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_parent'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], $data_array['comment_id']);
										array_push($response_array_mb_id, $rowddd['gss_mb_id']);
									}
								}
								else
								{
									if($rowddd['gss_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs3[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
										else
										{
											$newRegIdsIm3[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}

									}else{
										$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
								}
							}
						}

					}
					else
					{

						//해당 글에 같이 댓글 단 사람들(꼬리글)에게도 알림설정된 기기에 푸시 알림
						$sql_mbid = "select mb_id from {$data_array['write_table']} where wr_parent = {$data_array['wr_parent']} and wr_is_comment = 1 ";
						$result_mbid = sql_query($sql_mbid);
						$mb_ids = "";
						$mbids = array();
						for ($i=0; $rowmbid=sql_fetch_array($result_mbid); $i++)
						{
							if(is_array($exclude_targets) && in_array($rowmbid['mb_id'], $exclude_targets))
							{
								continue;
							}
							if(is_array($mbids))
							{
								if(!in_array($rowmbid['mb_id'],$mbids))
								{
									array_push($mbids, $rowmbid['mb_id']);
									if($mb_ids == ""){
										$mb_ids = "'".$rowmbid['mb_id']."'";
									}else{
										
										$mb_ids .= ",'".$rowmbid['mb_id']."'";
									}
								}
								else
								{
									continue;
								}
							}
							else
							{
								array_push($mbids, $rowmbid['mb_id']);
								if($mb_ids == ""){
									$mb_ids = "'".$rowmbid['mb_id']."'";
								}else{
									$mb_ids .= ",'".$rowmbid['mb_id']."'";
								}
							}
						}

						if(count($mbids) > 0)
						{

							$sql = "select count(*) as cnt from g5_gnupushapp_gcmregid where gpr_setting_mycom_tail = 'Y' and gpr_mb_id in ({$mb_ids}) ";
							$row_t = sql_fetch($sql);

							if($row_t['cnt'] > 0){

								$pushchannel2 = "normal";

								$title2 = $title;
								$ticker = $title;
								$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
								$etc2 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
							
								$sql = "select gpr_mb_id,gpr_reg_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_mycom_tail = 'Y' and gpr_mb_id in ({$mb_ids}) ";
								$devices_tail = sql_query($sql);
								$mb_id_ddd = array();
								for ($i=1; $rowddd=sql_fetch_array($devices_tail); $i++)
								{
									if($rowddd['gpr_sort'] == 'I'){
										$other_setting_array = unserialize($rowddd['gpr_other_setting']);
									}

									if(is_array($exclude_targets) && in_array($rowddd['gpr_mb_id'], $exclude_targets))
									{
										continue;
									}
									else
									{
										if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
										{
											if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
											{
												$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
												$count_badge++;
												$stringBadge = "badgeN" . strval($count_badge);
												if($rowddd['gpr_sort'] == 'I'){
													if($other_setting_array[0] == 'sound')
													{
														$newRegIdsIbs2[$stringBadge][] = $rowddd['gpr_reg_id'];
													}
													else
													{
														$newRegIdsIbm2[$stringBadge][] = $rowddd['gpr_reg_id'];
													}

												}else{
													if($rowddd['gpr_os_version'] >= 8){
														$devices_newAndroid_select2[$stringBadge][] = $rowddd['gpr_reg_id'];
													}else{
														$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
													}
												}
											}
											else
											{
												if($rowddd['gpr_sort'] == 'I'){
													if($other_setting_array[0] == 'sound')
													{
														$newRegIdsIs2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
													}
													else
													{
														$newRegIdsIm2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
													}

												}else{
													$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
												}
											}

											$msg_subject = $ticker." [".cut_str($content,40,'...')."]";
											if(!is_array($response_array_mb_id) || !in_array($rowddd['gpr_mb_id'], $response_array_mb_id)){
												insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_parent'], $msg_subject, $address, $rowddd['gpr_mb_id'], $data_array['member_id'], $data_array['wr_name'], $data_array['comment_id']);
												array_push($response_array_mb_id, $rowddd['gpr_mb_id']);
											}
										}
										else
										{
											if($rowddd['gpr_sort'] == 'I'){
												if($other_setting_array[0] == 'sound')
												{
													$newRegIdsIs2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
												}
												else
												{
													$newRegIdsIm2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
												}

											}else{
												$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
											}
										}
									}
									array_push($mb_id_ddd, $rowddd['gpr_mb_id']);
								}
								$array_mem_id = array_unique($mb_id_ddd);
								if(count($array_mem_id) > 0)
								{
									for ($ii=0; $ii<count($array_mem_id); $ii++)
									{
										if(is_array($exclude_targets) && in_array($array_mem_id[$ii], $exclude_targets))
										{
											continue;
										}else{
											array_push($exclude_targets, $array_mem_id[$ii]);
										}
									}
								}
							}
						}

						if($gnu_config['choose_board'] != "X" && ($gnu_config['choose_board'] != "N" || ($gnu_config['choose_board_s'] != "N" && $gnu_config['choose_board_s'] != "F2" && $gnu_config['choose_board_s'] != "Y")))
						{

							$setting = "newcom";

							$devices_num = get_subscribe_device_push('count', $setting, $setting_board_table, $setting_ca_name, $data_array['board_grant_c']);
							
							if($devices_num > $push_div_num)
							{
								$count_p = ceil($devices_num / $push_div_num);

								$data_array['exclude_targets'] = $exclude_targets;
								
								for($i=0;$i<$count_p;$i++)
								{
									$data_array['m_page'] = $i+1;

									$page_num = $i+1;

									$board_subject = $data_array['board_subject'];
									$gp_target_url = G5_BBS_URL.'/board.php?bo_table='.$data_array['bo_table'].'&wr_id='.$data_array['wr_parent'].'#c_'.$data_array['comment_id'];
									$gp_target_title = strip_tags(cut_str($data_array['wr_content'],50,''));
									$gp_target_title = $gp_target_title."[".$page_num."/".$count_p."]";
									gnu_send_socket($data_array, $board_subject, 'new_comment', $gp_target_url, $gp_target_title);
								}
								

							}
							else
							{
								$pushchannel3 = "normal";
								$title3 = $title;
								$ticker = $title;
								$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
								$etc3 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
								$devices_select_array = get_subscribe_device_push('array', $setting, $setting_board_table, $setting_ca_name, $data_array['board_grant_c']);

								for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
								{
									if($rowddd['gss_sort'] == 'I'){
										$other_setting_array = unserialize($rowddd['gss_other_setting']);
										$board_keyword = $other_setting_array[1];
										if(strlen($board_keyword) > 0)
										{
											if(strpos($board_keyword, ",") !== false)
											{
												$board_keyword_array = explode(',', $board_keyword);
												$keyword_exist = false;
												foreach($board_keyword_array as $value_keyword)
												{
													if(strpos($content, $value_keyword) !== false)
													{
														$keyword_exist = true;
													}
												}

												if(!$keyword_exist)
												{
													continue;
												}
											}
											else
											{
												if(!(strpos($content, $board_keyword) !== false))
												{
													continue;
												}
											}

										}

									}
									if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
									{
										continue;
									}
									else
									{

										if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
										{
											if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
											{
												$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
												$count_badge++;
												$stringBadge = "badgeN" . strval($count_badge);
												if($rowddd['gss_sort'] == 'I'){
													if($other_setting_array[0] == 'sound')
													{
														$newRegIdsIbs3[$stringBadge][] = $rowddd['gss_reg_id'];
													}
													else
													{
														$newRegIdsIbm3[$stringBadge][] = $rowddd['gss_reg_id'];
													}

												}else{
													if($rowddd['gpr_os_version'] >= 8){
														$devices_newAndroid_select3[$stringBadge][] = $rowddd['gss_reg_id'];
													}else{
														$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
													}
												}
											}
											else
											{
												if($rowddd['gss_sort'] == 'I'){
													if($other_setting_array[0] == 'sound')
													{
														$newRegIdsIs3[floor($i/1000)][] = $rowddd['gss_reg_id'];
													}
													else
													{
														$newRegIdsIm3[floor($i/1000)][] = $rowddd['gss_reg_id'];
													}

												}else{
													$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}
											}

											$msg_subject = $ticker." [".cut_str($content,40,'...')."]";
											if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
												insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_parent'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], $data_array['comment_id']);
												array_push($response_array_mb_id, $rowddd['gss_mb_id']);
											}
										}
										else
										{
											if($rowddd['gss_sort'] == 'I'){
												if($other_setting_array[0] == 'sound')
												{
													$newRegIdsIs3[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}
												else
												{
													$newRegIdsIm3[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}

											}else{
												$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
										}
									}
								}
							}
						}
					}
				}
			}
			elseif($func == "comment_reply")
			{

				//부모댓글 mb_id 가져오기
				$str_length = strlen($data_array['comment_reply']);

				if($str_length >= 2)
				{
					$wr_reply_p = substr($data_array['comment_reply'], 0, -1);
					$sql_p = "select mb_id from {$data_array['write_table']} where wr_parent = {$data_array['wr_parent']} and wr_comment = {$data_array['comment']} and wr_comment_reply = '{$wr_reply_p}'";
					$row_p = sql_fetch($sql_p);
					$pc_mb_id = $row_p['mb_id'];
				}
				else
				{
					$sql_p = "select * from {$data_array['write_table']} where wr_parent = {$data_array['wr_parent']} and wr_comment = {$data_array['comment']} ";
					$wr_p = sql_query($sql_p);
					for ($i=0; $row_p=sql_fetch_array($wr_p); $i++)
					{
						if($row_p['wr_comment_reply'] == "")
						{
							$pc_mb_id = $row_p['mb_id'];
						}
					}
				}

				//비밀대댓글일 경우 부모댓글에만 푸시 알림
				if($data_array['secret'] == 'secret')
				{
					if($pc_mb_id && $pc_mb_id != $data_array['member_id'])
					{
						$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_mycom_com = 'Y' and gpr_mb_id = '{$pc_mb_id}' ";
						$devices_a = sql_query($sql);
						for ($i=1; $rowddd=sql_fetch_array($devices_a); $i++)
						{
							if($rowddd['gpr_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gpr_other_setting']);
							}
							if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}else{
											$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}

									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
						$title1 = $data_array['wr_name'] . "님이 회원님의 댓글에 비밀 댓글을 달았습니다.";
						$ticker = $title1;
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
						array_push($exclude_targets, $pc_mb_id);
					}
				}
				else
				{
					//일반 대댓글일 경우 - 4가지 푸시 알림작동

					// 1. 부모댓글에 푸시 알림
					if($pc_mb_id && $pc_mb_id != $data_array['member_id'])
					{
						$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_mycom_com = 'Y' and gpr_mb_id = '{$pc_mb_id}' ";
						$devices_a = sql_query($sql);
						for ($i=1; $rowddd=sql_fetch_array($devices_a); $i++)
						{
							if($rowddd['gpr_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gpr_other_setting']);
							}
							if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs2[$stringBadge][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIbm2[$stringBadge][] = $rowddd['gpr_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select2[$stringBadge][] = $rowddd['gpr_reg_id'];
										}else{
											$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIm2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}

									}else{
										$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
						$title2 = $data_array['wr_name'] . "님이 회원님의 댓글에 답변을 달았습니다.";
						$ticker = $title2;
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc2 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
						array_push($exclude_targets, $pc_mb_id);
					}

					// 2. comment_s 설정에 따라 해당 글 작성자에게 푸시 알림

					if($pc_mb_id && $p_mb_id && $pc_mb_id != $p_mb_id && $p_mb_id != $data_array['member_id'] && $gnu_config['comment_s'] == "A")
					{

						$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_mypost_com = 'Y' and gpr_mb_id = '{$p_mb_id}' ";
						$devices_a = sql_query($sql);
						for ($i=1; $rowddd=sql_fetch_array($devices_a); $i++)
						{
							if($rowddd['gpr_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gpr_other_setting']);
							}

							if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
							{
								if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
								{
									$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
									$count_badge++;
									$stringBadge = "badgeN" . strval($count_badge);
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}

									}else{
										if($rowddd['gpr_os_version'] >= 8){
											$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
										}else{
											$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
									}
								}
								else
								{
									if($rowddd['gpr_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}
										else
										{
											$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
										}

									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
						$title1 = $data_array['wr_name'] . "님이 회원님의 글에 대댓글을 달았습니다.";
						$ticker = $title1;
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
						array_push($exclude_targets, $p_mb_id);
					}

					//해당글이 비밀글일 경우 알림설정된 관리자에게도 푸시 알림함
					if(strpos($row_p['wr_option'], "secret") !== false)
					{
						$title3 = $title;
						$ticker = $title;
						$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
						$etc3 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

						$setting = "newcom";

						$include_mb_id = "";

						if($board_config['bo_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
						if($gr_id_pu)
						{
							$group_config = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id_pu' ");
							if($group_config['gr_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $group_config['gr_admin']);
						}
						
						if($gnu_config['build_sort'] == 'A')
						{
							if($config['as_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $config['as_admin']);
							
						}else{
							if(isset($board_config['bo_admin'])) $include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
						}

						$devices_admin = get_subscribe_device_push('array', $setting, $setting_board_table, $setting_ca_name, 10, "secret", $include_mb_id);
						for ($i=1; $rowddd=sql_fetch_array($devices_admin); $i++)
						{
							if($rowddd['gss_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gss_other_setting']);
							}
							if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
							{
								continue;
							}
							else
							{
								if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
								{
									if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
									{
										$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
										$count_badge++;
										$stringBadge = "badgeN" . strval($count_badge);
										if($rowddd['gss_sort'] == 'I'){
											if($other_setting_array[0] == 'sound')
											{
												$newRegIdsIbs3[$stringBadge][] = $rowddd['gss_reg_id'];
											}
											else
											{
												$newRegIdsIbm3[$stringBadge][] = $rowddd['gss_reg_id'];
											}

										}else{
											if($rowddd['gpr_os_version'] >= 8){
												$devices_newAndroid_select3[$stringBadge][] = $rowddd['gss_reg_id'];
											}else{
												$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
										}
									}
									else
									{
										if($rowddd['gss_sort'] == 'I'){
											if($other_setting_array[0] == 'sound')
											{
												$newRegIdsIs3[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
											else
											{
												$newRegIdsIm3[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}

										}else{
											$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
									}

									$msg_subject = $ticker." [".cut_str($content,40,'...')."]";
									if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
										insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_parent'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], $data_array['comment_id']);
										array_push($response_array_mb_id, $rowddd['gss_mb_id']);
									}
								}
								else
								{
									if($rowddd['gss_sort'] == 'I'){
										if($other_setting_array[0] == 'sound')
										{
											$newRegIdsIs3[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}
										else
										{
											$newRegIdsIm3[floor($i/1000)][] = $rowddd['gss_reg_id'];
										}

									}else{
										$devices_select3[floor($i/1000)][] = $rowddd['gss_reg_id'];
									}
								}
							}
						}

					}
					else
					{

						// 3. 꼬리글 푸시 알림

						$sql_mbid = "select mb_id from {$data_array['write_table']} where wr_parent = {$data_array['wr_parent']} and wr_is_comment = 1 ";
						$result_mbid = sql_query($sql_mbid);
						$mb_ids = "";
						$mbids = array();
						for ($i=0; $rowmbid=sql_fetch_array($result_mbid); $i++)
						{
							if(is_array($exclude_targets) && in_array($rowmbid['mb_id'], $exclude_targets))
							{
								continue;
							}
							if(is_array($mbids))
							{
								if(!in_array($rowmbid['mb_id'],$mbids))
								{
									array_push($mbids, $rowmbid['mb_id']);
									if($mb_ids == ""){
										$mb_ids = "'".$rowmbid['mb_id']."'";
									}else{
										$mb_ids .= ",'".$rowmbid['mb_id']."'";
									}
								}
								else
								{
									continue;
								}
							}
							else
							{
								array_push($mbids, $rowmbid['mb_id']);
								if($mb_ids == ""){
									$mb_ids = "'".$rowmbid['mb_id']."'";
								}else{
									$mb_ids .= ",'".$rowmbid['mb_id']."'";
								}
							}
						}

						if(count($mbids) > 0)
						{
							$sql = "select count(*) as cnt from g5_gnupushapp_gcmregid where gpr_setting_mycom_tail = 'Y' and gpr_mb_id in ({$mb_ids}) ";
							$row_t = sql_fetch($sql);

							if($row_t['cnt'] > 0){

								$pushchannel3 = "normal";

								$title3 = $title;
								$ticker = $title;
								$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
								$etc3 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
							
								$sql = "select * from g5_gnupushapp_gcmregid where gpr_setting_mycom_tail = 'Y' and gpr_mb_id in ({$mb_ids}) ";
								$devices_tail = sql_query($sql);
								$mb_id_ddd = array();
								for ($i=1; $rowddd=sql_fetch_array($devices_tail); $i++)
								{
									if($rowddd['gpr_sort'] == 'I'){
										$other_setting_array = unserialize($rowddd['gpr_other_setting']);
									}

									if(is_array($exclude_targets) && in_array($rowddd['gpr_mb_id'], $exclude_targets))
									{
										continue;
									}
									else
									{
										if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
										{
											if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
											{
												$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
												$count_badge++;
												$stringBadge = "badgeN" . strval($count_badge);
												if($rowddd['gpr_sort'] == 'I'){
													if($other_setting_array[0] == 'sound')
													{
														$newRegIdsIbs3[$stringBadge][] = $rowddd['gpr_reg_id'];
													}
													else
													{
														$newRegIdsIbm3[$stringBadge][] = $rowddd['gpr_reg_id'];
													}

												}else{
													if($rowddd['gpr_os_version'] >= 8){
														$devices_newAndroid_select3[$stringBadge][] = $rowddd['gpr_reg_id'];
													}else{
														$devices_select3[floor($i/1000)][] = $rowddd['gpr_reg_id'];
													}
												}
											}
											else
											{
												if($rowddd['gpr_sort'] == 'I'){
													if($other_setting_array[0] == 'sound')
													{
														$newRegIdsIs3[floor($i/1000)][] = $rowddd['gpr_reg_id'];
													}
													else
													{
														$newRegIdsIm3[floor($i/1000)][] = $rowddd['gpr_reg_id'];
													}

												}else{
													$devices_select3[floor($i/1000)][] = $rowddd['gpr_reg_id'];
												}
											}

											$msg_subject = $ticker." [".cut_str($content,40,'...')."]";
											if(!is_array($response_array_mb_id) || !in_array($rowddd['gpr_mb_id'], $response_array_mb_id)){
												insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_parent'], $msg_subject, $address, $rowddd['gpr_mb_id'], $data_array['member_id'], $data_array['wr_name'], $data_array['comment_id']);
												array_push($response_array_mb_id, $rowddd['gpr_mb_id']);
											}
										}
										else
										{
											if($rowddd['gpr_sort'] == 'I'){
												if($other_setting_array[0] == 'sound')
												{
													$newRegIdsIs3[floor($i/1000)][] = $rowddd['gpr_reg_id'];
												}
												else
												{
													$newRegIdsIm3[floor($i/1000)][] = $rowddd['gpr_reg_id'];
												}

											}else{
												$devices_select3[floor($i/1000)][] = $rowddd['gpr_reg_id'];
											}
										}
									}
									array_push($mb_id_ddd, $rowddd['gpr_mb_id']);
								}

								$array_mem_id = array_unique($mb_id_ddd);
								if(count($array_mem_id) > 0)
								{
									for ($ii=0; $ii<count($array_mem_id); $ii++)
									{
										if(is_array($exclude_targets) && in_array($array_mem_id[$ii], $exclude_targets))
										{
											continue;
										}else{
											array_push($exclude_targets, $array_mem_id[$ii]);
										}
									}
								}

							}
						}

						// 4. 알림설정된 모든 기기에게 푸시 알림

						if($gnu_config['choose_board'] != "X" && ($gnu_config['choose_board'] != "N" || ($gnu_config['choose_board_s'] != "N" && $gnu_config['choose_board_s'] != "F2" && $gnu_config['choose_board_s'] != "Y")))
						{
							$setting = "newcom";

							$devices_num = get_subscribe_device_push('count', $setting, $setting_board_table, $setting_ca_name, $data_array['board_grant_c']);
							
							if($devices_num > $push_div_num)
							{
								$count_p = ceil($devices_num / $push_div_num);

								$data_array['exclude_targets'] = $exclude_targets;
								
								for($i=0;$i<$count_p;$i++)
								{
									$data_array['m_page'] = $i+1;

									$page_num = $i+1;

									$board_subject = $data_array['board_subject'];
									$gp_target_url = G5_BBS_URL.'/board.php?bo_table='.$data_array['bo_table'].'&wr_id='.$data_array['wr_parent'].'#c_'.$data_array['comment_id'];
									$gp_target_title = strip_tags(cut_str($data_array['wr_content'],50,''));
									$gp_target_title = $gp_target_title."[".$page_num."/".$count_p."]";

									gnu_send_socket($data_array, $board_subject, 'new_comment', $gp_target_url, $gp_target_title);
								}
								

							}
							else
							{
								$pushchannel4 = "normal";
								$title4 = $title;
								$ticker = $title;
								$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
								$etc4 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
								$devices_select_array = get_subscribe_device_push('array', $setting, $setting_board_table, $setting_ca_name, $data_array['board_grant_c']);

								for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
								{
									if($rowddd['gss_sort'] == 'I'){
										$other_setting_array = unserialize($rowddd['gss_other_setting']);
										$board_keyword = $other_setting_array[1];
										if(strlen($board_keyword) > 0)
										{
											if(strpos($board_keyword, ",") !== false)
											{
												$board_keyword_array = explode(',', $board_keyword);
												$keyword_exist = false;
												foreach($board_keyword_array as $value_keyword)
												{
													if(strpos($content, $value_keyword) !== false)
													{
														$keyword_exist = true;
													}
												}

												if(!$keyword_exist)
												{
													continue;
												}
											}
											else
											{
												if(!(strpos($content, $board_keyword) !== false))
												{
													continue;
												}
											}

										}
									}
									if(is_array($exclude_targets) && in_array($rowddd['gss_mb_id'], $exclude_targets))
									{
										continue;
									}
									else
									{
										if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
										{
											if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
											{
												$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
												$count_badge++;
												$stringBadge = "badgeN" . strval($count_badge);
												if($rowddd['gss_sort'] == 'I'){
													if($other_setting_array[0] == 'sound')
													{
														$newRegIdsIbs4[$stringBadge][] = $rowddd['gss_reg_id'];
													}
													else
													{
														$newRegIdsIbm4[$stringBadge][] = $rowddd['gss_reg_id'];
													}

												}else{
													if($rowddd['gpr_os_version'] >= 8){
														$devices_newAndroid_select4[$stringBadge][] = $rowddd['gss_reg_id'];
													}else{
														$devices_select4[floor($i/1000)][] = $rowddd['gss_reg_id'];
													}
												}
											}
											else
											{
												if($rowddd['gss_sort'] == 'I'){
													if($other_setting_array[0] == 'sound')
													{
														$newRegIdsIs4[floor($i/1000)][] = $rowddd['gss_reg_id'];
													}
													else
													{
														$newRegIdsIm4[floor($i/1000)][] = $rowddd['gss_reg_id'];
													}

												}else{
													$devices_select4[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}
											}

											$msg_subject = $ticker." [".cut_str($content,40,'...')."]";
											if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
												insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_parent'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], $data_array['comment_id']);
												array_push($response_array_mb_id, $rowddd['gss_mb_id']);
											}
										}
										else
										{
											if($rowddd['gss_sort'] == 'I'){
												if($other_setting_array[0] == 'sound')
												{
													$newRegIdsIs4[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}
												else
												{
													$newRegIdsIm4[floor($i/1000)][] = $rowddd['gss_reg_id'];
												}

											}else{
												$devices_select4[floor($i/1000)][] = $rowddd['gss_reg_id'];
											}
										}
									}
								}

							}
						}
					}
				}
			}
			elseif($func == "counsel")
			{
				//관리자와 글 작성자에게만 푸시 알림되도록 함

				//먼저 해당 글 작성자에게 푸시 알림
				if($p_mb_id && $p_mb_id != $data_array['member_id'])
				{
					$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_mypost_com = 'Y' and gpr_mb_id = '{$p_mb_id}' ";
					$devices_parent = sql_query($sql);
					for ($i=1; $rowddd=sql_fetch_array($devices_parent); $i++)
					{
						if($rowddd['gpr_sort'] == 'I'){
								$other_setting_array = unserialize($rowddd['gpr_other_setting']);
						}

						if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
									}else{
										$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}
						}
						else
						{
							if($rowddd['gpr_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
						}
					}
					$title1 = $data_array['wr_name'] . "님이 회원님의 글에 새 댓글을 작성하였습니다.";
					$ticker = $title1;
					$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
					$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;
					array_push($exclude_targets, $p_mb_id);
				}

				//관리자에게 푸시 알림

				$setting = "notice";

				$include_mb_id = "";

				if($board_config['bo_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
				if($gr_id_pu)
				{
					$group_config = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id_pu' ");
					if($group_config['gr_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $group_config['gr_admin']);
				}
				
				if($gnu_config['build_sort'] == 'A')
				{
					if($config['as_admin']) $include_mb_id = get_mb_id_listq($include_mb_id, $config['as_admin']);
					
				}else{
					if(isset($board_config['bo_admin'])) $include_mb_id = get_mb_id_listq($include_mb_id, $board_config['bo_admin']);
				}

				$devices_admin = get_devices_push('array', $setting, 10, $data_array['bo_table'], $func, $include_mb_id);

				for ($i=1; $rowddd=sql_fetch_array($devices_admin); $i++)
				{
					if($rowddd['gpr_sort'] == 'I'){
						$other_setting_array = unserialize($rowddd['gpr_other_setting']);
					}

					if(is_array($exclude_targets) && in_array($rowddd['gpr_mb_id'], $exclude_targets))
					{
						continue;
					}
					else
					{
						if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
						{
							if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
							{
								$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
								$count_badge++;
								$stringBadge = "badgeN" . strval($count_badge);
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIbs2[$stringBadge][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIbm2[$stringBadge][] = $rowddd['gpr_reg_id'];
									}

								}else{
									if($rowddd['gpr_os_version'] >= 8){
										$devices_newAndroid_select2[$stringBadge][] = $rowddd['gpr_reg_id'];
									}else{
										$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
								}
							}
							else
							{
								if($rowddd['gpr_sort'] == 'I'){
									if($other_setting_array[0] == 'sound')
									{
										$newRegIdsIs2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}
									else
									{
										$newRegIdsIm2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
									}

								}else{
									$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
							}

							$msg_subject = $ticker." [".cut_str($content,40,'...')."]";
							if(!is_array($response_array_mb_id) || !in_array($rowddd['gpr_mb_id'], $response_array_mb_id)){
								insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_parent'], $msg_subject, $address, $rowddd['gpr_mb_id'], $data_array['member_id'], $data_array['wr_name'], $data_array['comment_id']);
								array_push($response_array_mb_id, $rowddd['gpr_mb_id']);
							}
						}
						else
						{
							if($rowddd['gpr_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}
								else
								{
									$newRegIdsIm2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
								}

							}else{
								$devices_select2[floor($i/1000)][] = $rowddd['gpr_reg_id'];
							}
						}
					}
				}
				$title2 = $data_array['wr_name'] . "님이 새 댓글을 작성하였습니다.";
				$ticker = $title2;
				$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
				$etc2 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

			}

		}
		else
		{
			$exclude_targets = $data_array['exclude_targets'];

			$setting = "newcom";
			$pushchannel = "normal";
			$title1 = $title;
			$ticker = $title;
			$bottom_text = $data_array['wr_name'] . "님 작성 / " . $data_array['board_subject'];
			$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

			$devices_select_array = get_subscribe_device_push_page($setting, $setting_board_table, $setting_ca_name, $data_array['board_grant_c'], $data_array['m_page'], $push_div_num, $func);

			for ($i=1; $rowddd=sql_fetch_array($devices_select_array); $i++)
			{
				if($rowddd['gss_sort'] == 'I'){
					$other_setting_array = unserialize($rowddd['gss_other_setting']);
					$board_keyword = $other_setting_array[1];
					if(strlen($board_keyword) > 0)
					{
						if(strpos($board_keyword, ",") !== false)
						{
							$board_keyword_array = explode(',', $board_keyword);
							$keyword_exist = false;
							foreach($board_keyword_array as $value_keyword)
							{
								if(strpos($content, $value_keyword) !== false)
								{
									$keyword_exist = true;
								}
							}

							if(!$keyword_exist)
							{
								continue;
							}
						}
						else
						{
							if(!(strpos($content, $board_keyword) !== false))
							{
								continue;
							}
						}

					}
				}
				if(is_array($exclude_targets) && in_array($rowddd['gpr_mb_id'], $exclude_targets))
				{
					continue;
				}
				else
				{
					if($rowddd['gss_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
					{
						if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
						{
							$count_badge = getBadge_by_mb_id($rowddd['gss_mb_id']);
							$count_badge++;
							$stringBadge = "badgeN" . strval($count_badge);
							if($rowddd['gss_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIbs1[$stringBadge][] = $rowddd['gss_reg_id'];
								}
								else
								{
									$newRegIdsIbm1[$stringBadge][] = $rowddd['gss_reg_id'];
								}

							}else{
								if($rowddd['gpr_os_version'] >= 8){
									$devices_newAndroid_select1[$stringBadge][] = $rowddd['gss_reg_id'];
								}else{
									$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
							}
						}
						else
						{
							if($rowddd['gss_sort'] == 'I'){
								if($other_setting_array[0] == 'sound')
								{
									$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}
								else
								{
									$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
								}

							}else{
								$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
							}
						}

						$msg_subject = $ticker." [".cut_str($content,40,'...')."]";
						if(!is_array($response_array_mb_id) || !in_array($rowddd['gss_mb_id'], $response_array_mb_id)){
							insert_Notification_list("comment", $data_array['bo_table'], $data_array['wr_parent'], $msg_subject, $address, $rowddd['gss_mb_id'], $data_array['member_id'], $data_array['wr_name'], $data_array['comment_id']);
							array_push($response_array_mb_id, $rowddd['gss_mb_id']);
						}
					}
					else
					{
						if($rowddd['gss_sort'] == 'I'){
							if($other_setting_array[0] == 'sound')
							{
								$newRegIdsIs1[floor($i/1000)][] = $rowddd['gss_reg_id'];
							}
							else
							{
								$newRegIdsIm1[floor($i/1000)][] = $rowddd['gss_reg_id'];
							}

						}else{
							$devices_select1[floor($i/1000)][] = $rowddd['gss_reg_id'];
						}
					}
				}
			}
		}

		break;

	case "new_memo" :

		$use_profile = "false";
		$profile_link = "none";
		$mb_icon_url = get_gnu_profile_image($data_array['member_id']);
		
		if($mb_icon_url){
			$use_profile = "true";
			$profile_link = $mb_icon_url;
		}else{
			$member_wr = get_member($data_array['member_id']);
			if($member_wr['mb_7']){
				$use_profile = "true";
				$profile_link = $member_wr['mb_7'];
			}
		}

		if($gnu_config['profile_p'] == "N")
		{
			$use_profile = "false";
			$profile_link = "none";
		}

		if($gnu_config['headsup_push_style'] == "Y")
		{
			$banner = "headsup";
		}

		$row_mem = get_member($data_array['member_id']);
		$mb_nick = $row_mem['mb_nick'];
		
		$title1 = $mb_nick . "님으로부터 쪽지가 도착했습니다.";
		$content = $data_array['me_memo'];
		$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), stripslashes($content));
		$address = G5_BBS_URL.'/memo_view.php?me_id='.$data_array['me_id'].'&kind=recv';

		$pushstyle = "normal";
		$image_src = "none";
		
		if($gnu_config['push_style'] == "Y")
		{
			$pushstyle = "big_text";
		}

		$recv_mb_id = $data_array['recv_mb_id'];

		$sql = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where gpr_setting_message = 'Y' and gpr_mb_id = '{$recv_mb_id}' ";
		$devices_a = sql_query($sql);
		for ($i=1; $rowddd=sql_fetch_array($devices_a); $i++)
		{
			if($rowddd['gpr_sort'] == 'I'){
				$other_setting_array = unserialize($rowddd['gpr_other_setting']);
			}
			if($rowddd['gpr_sync'] != 'N' && $gnu_config["mypushlist"] == "Y")
			{
				if($gnu_config['build_sort'] == 'A' || $gnu_config['build_sort'] == 'E' || $gnu_config["pushmsg"] == "Y")
				{
					$count_badge = getBadge_by_mb_id($rowddd['gpr_mb_id']);
					$count_badge++;
					$stringBadge = "badgeN" . strval($count_badge);
					if($rowddd['gpr_sort'] == 'I'){
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIbs1[$stringBadge][] = $rowddd['gpr_reg_id'];
						}
						else
						{
							$newRegIdsIbm1[$stringBadge][] = $rowddd['gpr_reg_id'];
						}

					}else{
						if($rowddd['gpr_os_version'] >= 8){
							$devices_newAndroid_select1[$stringBadge][] = $rowddd['gpr_reg_id'];
						}else{
							$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}
					}
				}
				else
				{
					if($rowddd['gpr_sort'] == 'I'){
						if($other_setting_array[0] == 'sound')
						{
							$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}
						else
						{
							$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
						}

					}else{
						$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
					}
				}
			}
			else
			{
				if($rowddd['gpr_sort'] == 'I'){
					if($other_setting_array[0] == 'sound')
					{
						$newRegIdsIs1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
					}
					else
					{
						$newRegIdsIm1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
					}

				}else{
					$devices_select1[floor($i/1000)][] = $rowddd['gpr_reg_id'];
				}
			}
		}
		$ticker = $title1;
		$bottom_text = $mb_nick . "님 작성";
		$etc1 = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text;

		break;

}

if(!$returnEnd)
{

	$send1 = "";
	$send2 = "";
	$send3 = "";
	$send4 = "";

	if($gp_type != "delete")
	{

		$etc1 = $etc1 . "ab&#ba" . $gp_type . "ab&#ba" . $banner;
		$etc2 = $etc2 . "ab&#ba" . $gp_type . "ab&#ba" . $banner;
		$etc3 = $etc3 . "ab&#ba" . $gp_type . "ab&#ba" . $banner;
		$etc4 = $etc4 . "ab&#ba" . $gp_type . "ab&#ba" . $banner;

	}

	$delete_push = false;

	$newRegIdsId = array();

	if(count($devices_newAndroid_select4) > 0 || count($devices_select4) > 0 || count($newRegIdsIbs4) > 0 || count($newRegIdsIbm4) > 0 || count($newRegIdsIs4) > 0 || count($newRegIdsIm4) > 0)
	{
		$send4 = gnupushsend($devices_select4, $title4, $content, $address, $etc4, $newRegIdsIbs4, $newRegIdsIbm4, $newRegIdsIs4, $newRegIdsIm4, $newRegIdsId, $newRegIdsId, $gp_type, $pushchannel, $devices_newAndroid_select4, $keypass);
	}
	if(count($devices_newAndroid_select3) > 0 || count($devices_select3) > 0 || count($newRegIdsIbs3) > 0 || count($newRegIdsIbm3) > 0 || count($newRegIdsIs3) > 0 || count($newRegIdsIm3) > 0)
	{
		$send3 = gnupushsend($devices_select3, $title3, $content, $address, $etc3, $newRegIdsIbs3, $newRegIdsIbm3, $newRegIdsIs3, $newRegIdsIm3, $newRegIdsId, $newRegIdsId, $gp_type, $pushchannel, $devices_newAndroid_select3, $keypass);
	}
	if(count($devices_newAndroid_select2) > 0 || count($devices_select2) > 0 || count($newRegIdsIbs2) > 0 || count($newRegIdsIbm2) > 0 || count($newRegIdsIs2) > 0 || count($newRegIdsIm2) > 0)
	{
		$send2 = gnupushsend($devices_select2, $title2, $content, $address, $etc2, $newRegIdsIbs2, $newRegIdsIbm2, $newRegIdsIs2, $newRegIdsIm2, $newRegIdsId, $newRegIdsId, $gp_type, $pushchannel, $devices_newAndroid_select2, $keypass);
	}
	if(count($devices_newAndroid_select1) > 0 || count($devices_select1) > 0 || count($newRegIdsIbs1) > 0 || count($newRegIdsIbm1) > 0 || count($newRegIdsIs1) > 0 || count($newRegIdsIm1) > 0 || count($devices_selectIphone) > 0)
	{
		$send1 = gnupushsend($devices_select1, $title1, $content, $address, $etc1, $newRegIdsIbs1, $newRegIdsIbm1, $newRegIdsIs1, $newRegIdsIm1, $devices_selectIphone, $newRegIdsId, $gp_type, $pushchannel, $devices_newAndroid_select1, $keypass);
	}

	if(count($devices_newAndroid_select1) > 0 || count($devices_newAndroid_select2) > 0 || count($devices_newAndroid_select3) > 0 || count($devices_newAndroid_select4) > 0 || count($devices_select4) >0 || count($newRegIdsIbs4) > 0 || count($newRegIdsIbm4) > 0 || count($newRegIdsIs4) > 0 || count($newRegIdsIm4) > 0 || count($devices_select3) >0 || count($newRegIdsIbs3) > 0 || count($newRegIdsIbm3) > 0 || count($newRegIdsIs3) > 0 || count($newRegIdsIm3) > 0 || count($devices_select2) >0 || count($newRegIdsIbs2) > 0 || count($newRegIdsIbm2) > 0 || count($newRegIdsIs2) > 0 || count($newRegIdsIm2) > 0 || count($devices_select1) >0 || count($newRegIdsIbs1) > 0 || count($newRegIdsIbm1) > 0 || count($newRegIdsIs1) > 0 || count($newRegIdsIm1) > 0 || count($devices_selectIphone) > 0){

	}else{
		$delete_push = true;
	}

	if($delete_push)
	{
		$sql = " delete from g5_gnupushapp_push where gp_pushid = '{$keypass}' ";
		sql_query($sql);
	}
	else
	{

		$total_push = 0;
		$success_push = 0;
		$error_push = 0;

		
		if($send4 != "")
		{
			$send4_array = explode("-", $send4);
			$total_push = $total_push + $send4_array[0];
			$success_push = $success_push + $send4_array[1];
			$error_push = $error_push + $send4_array[2];
		}
		if($send3 != "")
		{
			$send3_array = explode("-", $send3);
			$total_push = $total_push + $send3_array[0];
			$success_push = $success_push + $send3_array[1];
			$error_push = $error_push + $send3_array[2];
		}
		if($send2 != "")
		{
			$send2_array = explode("-", $send2);
			$total_push = $total_push + $send2_array[0];
			$success_push = $success_push + $send2_array[1];
			$error_push = $error_push + $send2_array[2];
		}
		if($send1 != "")
		{
			$send1_array = explode("-", $send1);
			$total_push = $total_push + $send1_array[0];
			$success_push = $success_push + $send1_array[1];
			$error_push = $error_push + $send1_array[2];
		}

		if($total_push == 0)
		{

			$sql = " delete from g5_gnupushapp_push where gp_pushid = '{$keypass}' ";
			sql_query($sql);

		}
		else
		{

			$data_text = "총발송량 : " . $total_push . "  /  성공 : " . $success_push . "  /  에러 및 삭제 : " . $error_push;

			$sql = " update g5_gnupushapp_push
					set gp_issend = 'Y',
					gp_text = '{$data_text}'
					where gp_pushid = '{$keypass}' ";
			sql_query($sql);
		}
	}
}


exit();

?>