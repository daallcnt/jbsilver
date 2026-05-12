<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$data_to_app1 = "none";

if($str_mp == $masterpassword)
{
	$level = 1;
	$social_provider = "none";
	$sort = "A";
	$profile = "false";
	$profile_link = "none";
	$gnu_is_admin = "false";
	$social_gnu = "false";
	$member_info_val = "none";
	$sync_data = "N";
	$level_point = 1;
	$iphonesound = "sound";
	$board_keyword = "";
	$youngcart_keyword = "";
	$chatpush = "true";
	$youngcart = "false";
	$setting_youngcart_all = "false";
	$badge = "0";
	$member_extra_info = "none";
	$mb_id_gnupush = "false";
	$nick_name = "false";
	$group = "none";
	$point = 0;
	$exp_val = 0;
	$exp_per_val = 0;
	$exp_up_val = 0;
	$use_youngcart = "N";

	if($gnu_config['build_sort'] == "A")
	{
		$l_title= array("없음", $xp['xp_grade1'], $xp['xp_grade2'], $xp['xp_grade3'], $xp['xp_grade4'], $xp['xp_grade5'], $xp['xp_grade6'], $xp['xp_grade7'], $xp['xp_grade8'], $xp['xp_grade9'], $xp['xp_grade10']);
	}
	else
	{
		$l_title= array("없음", "레벨1", "레벨2", "레벨3", "레벨4", "레벨5", "레벨6", "레벨7", "레벨8", "레벨9", "레벨10", "레벨11", "레벨12", "레벨13", "레벨14", "레벨15", "레벨16", "레벨17", "레벨18", "레벨19", "레벨20", "레벨21", "레벨22", "레벨23", "레벨24", "레벨25", "레벨26", "레벨27", "레벨28", "레벨29", "레벨30");
	}
	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
		$sync = $row_1['gpr_sync'];
		$sort = $row_1['gpr_sort'];
		$mb_id_gnupush = $row_1['gpr_mb_id'];

		$setting_newpost = $row_1['gpr_setting_newpost'];
		$setting_newcom = $row_1['gpr_setting_newcom'];
		$setting_reply = $row_1['gpr_setting_myreply'];
		$setting_mypost_com = $row_1['gpr_setting_mypost_com'];
		$setting_mycom_com = $row_1['gpr_setting_mycom_com'];
		$setting_mycom_tail = $row_1['gpr_setting_mycom_tail'];
		$setting_notice = $row_1['gpr_setting_notice'];
		$setting_message = $row_1['gpr_setting_message'];
		$setting_mention = $row_1['gpr_setting_mention'];
		$setting_recommendation = $row_1['gpr_setting_recommendation'];
		$setting_marketing = $row_1['gpr_setting_marketing'];
		$setting_youngcart = $row_1['gpr_setting_youngcart'];
		$setting_chat = $row_1['gpr_setting_chat'];
		$youngcart_all = $row_1['gpr_setting_youngcart_all'];

		if($youngcart_all == "Y")
		{
			$setting_youngcart_all = "true";
		}

		if($setting_chat == "N")
		{
			$chatpush = "false";
		}

		if($setting_youngcart == "Y")
		{
			$youngcart = "true";
		}

		$setting = $setting_newpost . "-" . $setting_newcom . "-" . $setting_reply . "-" . $setting_mypost_com . "-" . $setting_mycom_com . "-" . $setting_mycom_tail . "-" . $setting_notice . "-" . $setting_message . "-" . $setting_mention . "-" . $setting_recommendation . "-" . $setting_marketing;

		$setting = str_replace(array('Y', 'N'), array('true', 'false'), $setting);
		
		$gpr_social = $row_1['gpr_social'];
		$last_ac = $row_1['gpr_last_login'];
		$last_login = substr($last_ac,0,10);

		if($sync != "N" && $gnu_config["mypushlist"] == "Y")
		{
			$badge = getBadge_by_mb_id($mb_id_gnupush);
		}

		if($is_member)
		{
			$now_login = "Y";
		}
		else
		{
			$now_login = "N";
		}

		if($sort == 'I'){
			$other_setting_array = unserialize($row_1['gpr_other_setting']);
			$iphonesound = $other_setting_array[0];
			$board_keyword = $other_setting_array[1];
			$youngcart_keyword = $other_setting_array[2];
		}

		if($sync == "Y" || $sync == "D")
		{
			$sync_data = "Y";
			$member_yd = get_member($mb_id_gnupush);
			$level = intval($member_yd['mb_level']);
			$group = $l_title[$level];
			if($level == 10) $gnu_is_admin = "true";
			$point = $member_yd['mb_point'];
			$lastlogin = substr($member_yd['mb_today_login'],0,10);
			$nick_name = $member_yd['mb_nick'];
			$mb_icon_url = get_gnu_profile_image($member_yd['mb_id']);
			if($mb_icon_url){
				$profile = "true";
				$profile_link = $mb_icon_url;
			}
			if(stristr($gpr_social, "#$%"))
			{

			}
			else if($gpr_social != 'none')
			{
				$social_gnu = "true";
				$social_provider = $gpr_social;
			}
		}
		else if($sync == "S")
		{
			$sync_data = "Y";
			if($gpr_social != "none")
			{
				$social_gnu = "true";
				$gpr_social_Array = explode("#$%", $gpr_social);
				if($gpr_social_Array[1] == "mb_id" && count($gpr_social_Array) > 2)
				{
					$member_so = get_member($gpr_social_Array[2]);
					$level = intval($member_so['mb_level']);
					$group = $l_title[$level];
					$point = $member_so['mb_point'];
					$nick_name = $member_so['mb_nick'];
					if($gnu_config['build_sort'] == "A")
					{
						$mb_dir = substr($member_so['mb_id'],0,2);
						$is_photo = (is_file(G5_DATA_PATH.'/apms/photo/'.$mb_dir.'/'.$member_so['mb_id'].'.jpg')) ? true : false;

						if($is_photo)
						{
							$profile = "true";
							$profile_link = G5_DATA_URL.'/apms/photo/'.$mb_dir.'/'.$member_so['mb_id'].'.jpg';
						}
					}
					else
					{
						if($member_so['mb_7'])
						{
							$profile = "true";
							$profile_link = $member_so['mb_7'];
						}
					}
				}else{
					$memb_social = unserialize($gpr_social_Array[1]);
					$nick_name = $memb_social['mb_nick'];
					$level = intval($memb_social['mb_level']);
					$group = $l_title[$level];
					$point = 0;
				}
				$social_provider = $gpr_social_Array[0];
			}
		}
		else
		{
			$level = 1;
			$group = $l_title[$level];
			$point = 0;
		}

		if($sync != "N"){
			if($gnu_config['build_sort'] == "A")
			{
				$member_level = get_member($mb_id_gnupush);
				$level_point = intval($member_level['as_level']);
			}
		}

		$dis_mem_info = "none";

		if(is_array($gnu_config['mem_info']) && count($gnu_config['mem_info']) > 0)
		{
			foreach($gnu_config['mem_info'] as $val_meminfo)
			{
				if($dis_mem_info == "none")
				{
					$dis_mem_info = $val_meminfo;
				}
				else
				{
					$dis_mem_info = $dis_mem_info . "-" . $val_meminfo;
				}
			}
		}

		if(is_array($gnu_config['mem_info']) && in_array("group", $gnu_config['mem_info']))
		{
			$member_info_val = $group;
		}

		if(is_array($gnu_config['mem_info']) && in_array("level", $gnu_config['mem_info']))
		{
			if($gnu_config['build_sort'] == "A"){
				if($member_info_val == "none")
				{
					$member_info_val = "Lv." . $level_point;
				}
				else
				{
					$member_info_val .= " / Lv." . $level_point;
				}
				if(is_array($gnu_config['mem_info']) && in_array("exp", $gnu_config['mem_info']))
				{					
					$member_A = apms_member($mb_id_gnupush);
					$exp_val = $member_A['exp'];
					$exp_per_val = $member_A['exp_per'];
					$exp_up_val = $member_A['exp_up'];
					$member_extra_info = $level_point . "&@" . number_format($member_A['exp']) . "&@" . $member_A['exp_per'] . "&@" . number_format($member_A['exp_up']);
					$member_info_val .= "(" . $member_A['exp_per'] . "%)";
				}
			}else{
				if($member_info_val == "none")
				{
					$member_info_val = "Level : " . $level;
				}
				else
				{
					$member_info_val .= " / Level : " . $level;
				}
			}
		}

		if(is_array($gnu_config['mem_info']) && in_array("point", $gnu_config['mem_info']))
		{
			if($member_info_val == "none")
			{
				$member_info_val = "Point : " . number_format($point);
			}
			else
			{
				$member_info_val .= " / Point : " . number_format($point);
			}

		}

		if(defined('G5_USE_SHOP') && G5_USE_SHOP)
		{
			$use_youngcart = $gnu_config['use_youngcart'];
			$youngcart_category = $gnu_config['youngcart_category'];
			$youngcart_category_default = $gnu_config['youngcart_category_default'];
			$youngcart_name = $gnu_config['youngcart_name'];
		}else{
			$use_youngcart = "N";
			$youngcart_category = "gnupushapp123none";
			$youngcart_category_default = "gnupushapp123none";
			$youngcart_name = "gnupushapp123none";
		}

		if($sync_data == "Y")
		{
			$data_to_app1 = $sync_data . "/-" . $mb_id_gnupush . "/-" . $nick_name . "/-" . $setting . "/-" . $gnu_config['choose_board_s'] . "/-" . $now_login . "/-" . $social_gnu . "/-" . $social_provider . "/-" . $profile . "/-" . $profile_link . "/-" . $member_info_val . "/-" . $gnu_is_admin . "/-" . $gnu_config['category_default'] . "/-" .$use_youngcart . "/-" .$youngcart_category . "/-" .$youngcart_category_default . "/-" . $youngcart_name . "/-" . $iphonesound . "/-" . $board_keyword . "/-" . $youngcart_keyword . "/-" . $youngcart . "/-" . $badge . "/-" . $member_extra_info . "/-" . $chatpush . "/-" . $level . "/-" . $dis_mem_info;
		}
		else
		{
			$data_to_app1 = $sync_data . "/-false/-false/-" . $setting . "/-" . $gnu_config['choose_board_s'] . "/-" . $now_login . "/-false/-none/-" . $profile . "/-" . $profile_link . "/-" . $member_info_val . "/-false/-" . $gnu_config['category_default'] . "/-" .$use_youngcart . "/-" .$youngcart_category . "/-" .$youngcart_category_default . "/-" . $youngcart_name . "/-" . $iphonesound . "/-" . $board_keyword . "/-" . $youngcart_keyword . "/-" . $youngcart . "/-" . $badge . "/-" . $member_extra_info . "/-" . $chatpush . "/-" . $level . "/-" . $dis_mem_info;
		}

	}

	$setting_board = "";

	foreach($gnu_config['module_order'] as $key)
	{
		$key_bo_table = "";
		if(strpos($key, "#/") !== false)
		{  
			$key_array = explode("#/", $key);
			$key_bo_table = $key_array[1];
		}
		else
		{
			$key_array = explode("_", $key);
			if(count($key_array) > 2)
			{
				for($ii=0;$ii<count($key_array);$ii++)
				{
					if($ii==0) continue;
					if($ii==1)
					{
						$key_bo_table = $key_array[$ii];
						continue;
					}
					$key_bo_table .= "_".$key_array[$ii];
				}
			}
			else
			{
				$key_bo_table = $key_array[1];
			}
		}

		$go_for_it = true;
		if(is_array($gnu_config['no_use_module_srls']) && in_array($key_bo_table, $gnu_config['no_use_module_srls'])) $go_for_it = false;
		if(is_array($gnu_config['only_admin_push_module_srls']) && in_array($key_bo_table, $gnu_config['only_admin_push_module_srls'])) $go_for_it = false;
		if(is_array($gnu_config['notice_module_srls']) && in_array($key_bo_table, $gnu_config['notice_module_srls'])) $go_for_it = false;
		if(is_array($gnu_config['group_module_srls']) && in_array($key_bo_table, $gnu_config['group_module_srls']))
		{
			if($sync_data == 'N')
			{
				$go_for_it = false;
			}
			else
			{
				$board_config = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$key_bo_table' ");
				$gr_id_pu = $board_config['gr_id'];
				$res_this = sql_fetch("select count(*) as 'cnt' from {$g5['group_member_table']} where gr_id = '$gr_id_pu' and mb_id = '$mb_id_gnupush'");
				if($res_this['cnt'] == 0){

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
					for ($i=0; $admin_mb_id=sql_fetch_array($admin_result); $i++)
					{
						array_push($array_mb_id, $admin_mb_id['mb_id']);
					}

					if(!in_array($mb_id_gnupush, $array_mb_id)){
						$go_for_it = false;
					}
				}
			}
		}


		if($go_for_it)
		{

			$sql = " select * from {$g5['board_table']} where bo_table = '{$key_bo_table}'";
			$row = sql_fetch($sql);

			unset($grnt);
			$grnt = "false";
			if($gnu_config['board_grant'] == "Y" || $gnu_config['board_grant'] == "D")
			{
				if($gnu_config['build_sort'] == "A"){

					if($row['as_min'] != 0 && $row['as_max'] != 0){
						if($level_point < $row['as_min']) $grnt = "true";
						if($level_point > $row['as_max']) $grnt = "true";
					}elseif($row['as_grade'] > 1 || $row['as_equal'] != 0){
						if($row['as_equal'] == 0){
							if($level < $row['as_grade']) $grnt = "true";
						}else{
							if($level != $row['as_grade']) $grnt = "true";
						}
					}else{
						if($level < $row[$gnu_config['board_grant_c']]) $grnt = "true";
					}

				}else{
					if($level < $row[$gnu_config['board_grant_c']]) $grnt = "true";
				}
			}

			if($gnu_config['board_grant'] == "N" || $gnu_config['board_grant'] == "D" || $grnt == "false")
			{

				$browser_t = strip_tags($row['bo_subject']);

				if(is_array($gnu_config['category_module_srls']) && in_array($key_bo_table, $gnu_config['category_module_srls']))
				{
					

					if($row['bo_use_category'] == 1 && $row['bo_category_list'])
					{

						$aaa = explode("|",$row['bo_category_list']);

						foreach($aaa as $category)
						{
							$true_or_false = "false";
							$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where gss_is_youngcart = 'N' and gss_bo_table = '{$key_bo_table}' and gss_ca_name = '{$category}' and gss_post_subscribe = 'Y' and gss_reg_id = '{$reg_id}' ";
							$row = sql_fetch($query);
							if($row['cnt'] > 0)
							{
								$true_or_false = "true";
							}

							$browser_t .= "###" . $category . "%%%0%%%" . $category . "%%%" . $true_or_false;
						}

					}

				}

				$true_or_false = "false";
				$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where gss_is_youngcart = 'N' and gss_bo_table = '{$key_bo_table}' and gss_ca_name = 'none' and gss_post_subscribe = 'Y' and gss_reg_id = '{$reg_id}' ";
				$row = sql_fetch($query);
				if($row['cnt'] > 0)
				{
					$true_or_false = "true";
				}

				if($setting_board == "")
				{
					$setting_board = $browser_t . "%#" . $key_bo_table . "%#" . $grnt . "%#" . $true_or_false;
				}
				else
				{
					$setting_board = $setting_board . "/-" . $browser_t . "%#" . $key_bo_table . "%#" . $grnt . "%#" . $true_or_false;
				}

			}
		}

	}

	if(defined('G5_USE_SHOP') && G5_USE_SHOP)
	{
		$setting_youngcart = $gnu_config['youngcart_name'];
	}else{
		$setting_youngcart = "gnupushapp123none";
	}

	$final_true_or_false = $setting_youngcart_all;

	if($gnu_config['youngcart_category'] == "Y" && defined('G5_USE_SHOP') && G5_USE_SHOP)
	{
		//1단계****************************
		$sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '2' and ca_use = '1' order by ca_order ";
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++)
		{
			$ca_id = $row['ca_id'];
			$ca_name = $row['ca_name'];

			$true_or_false = "false";
			$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where gss_is_youngcart = 'Y' and gss_ca_name = '{$ca_id}' and gss_post_subscribe = 'Y' and gss_reg_id = '{$reg_id}' ";
			$row = sql_fetch($query);
			if($row['cnt'] > 0)
			{
				$true_or_false = "true";
			}

			if($true_or_false == "false") $final_true_or_false = "false";

			$setting_youngcart .= "###" . $ca_id . "%%%0%%%" . $ca_name . "%%%" . $true_or_false;

			//2단계****************************
			$sql2 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '4' and ca_use = '1' and ca_id like '{$ca_id}%' order by ca_order ";
			$result2 = sql_query($sql2);
			for ($i=0; $row2=sql_fetch_array($result2); $i++)
			{
				$ca_id2 = $row2['ca_id'];
				$ca_name2 = $row2['ca_name'];

				$true_or_false = "false";
				$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where gss_is_youngcart = 'Y' and gss_ca_name = '{$ca_id2}' and gss_post_subscribe = 'Y' and gss_reg_id = '{$reg_id}' ";
				$row = sql_fetch($query);
				if($row['cnt'] > 0)
				{
					$true_or_false = "true";
				}

				if($true_or_false == "false") $final_true_or_false = "false";

				$setting_youngcart .= "###" . $ca_id2 . "%%%1%%%" . $ca_name2 . "%%%" . $true_or_false;

				//3단계****************************
				$sql3 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '6' and ca_use = '1' and ca_id like '{$ca_id2}%' order by ca_order ";
				$result3 = sql_query($sql3);
				for ($i=0; $row3=sql_fetch_array($result3); $i++)
				{
					$ca_id3 = $row3['ca_id'];
					$ca_name3 = $row3['ca_name'];

					$true_or_false = "false";
					$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where gss_is_youngcart = 'Y' and gss_ca_name = '{$ca_id3}' and gss_post_subscribe = 'Y' and gss_reg_id = '{$reg_id}' ";
					$row = sql_fetch($query);
					if($row['cnt'] > 0)
					{
						$true_or_false = "true";
					}

					if($true_or_false == "false") $final_true_or_false = "false";

					$setting_youngcart .= "###" . $ca_id3 . "%%%2%%%" . $ca_name3 . "%%%" . $true_or_false;

					//4단계****************************
					$sql4 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '8' and ca_use = '1' and ca_id like '{$ca_id3}%' order by ca_order ";
					$result4 = sql_query($sql4);
					for ($i=0; $row4=sql_fetch_array($result4); $i++)
					{
						$ca_id4 = $row4['ca_id'];
						$ca_name4 = $row4['ca_name'];

						$true_or_false = "false";
						$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where gss_is_youngcart = 'Y' and gss_ca_name = '{$ca_id4}' and gss_post_subscribe = 'Y' and gss_reg_id = '{$reg_id}' ";
						$row = sql_fetch($query);
						if($row['cnt'] > 0)
						{
							$true_or_false = "true";
						}

						if($true_or_false == "false") $final_true_or_false = "false";

						$setting_youngcart .= "###" . $ca_id4 . "%%%3%%%" . $ca_name4 . "%%%" . $true_or_false;

						//5단계****************************
						$sql5 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '10' and ca_use = '1' and ca_id like '{$ca_id4}%' order by ca_order ";
						$result5 = sql_query($sql5);
						for ($i=0; $row5=sql_fetch_array($result5); $i++)
						{
							$ca_id5 = $row5['ca_id'];
							$ca_name5 = $row5['ca_name'];

							$true_or_false = "false";
							$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where gss_is_youngcart = 'Y' and gss_ca_name = '{$ca_id5}' and gss_post_subscribe = 'Y' and gss_reg_id = '{$reg_id}' ";
							$row = sql_fetch($query);
							if($row['cnt'] > 0)
							{
								$true_or_false = "true";
							}

							if($true_or_false == "false") $final_true_or_false = "false";

							$setting_youngcart .= "###" . $ca_id5 . "%%%4%%%" . $ca_name5 . "%%%" . $true_or_false;

						}

					}

				}

			}


		}

		$setting_youngcart = $setting_youngcart . "%#gnupushappyoungcart5%#false%#" . $final_true_or_false;
	}

	$setting_board .= "adfrewrgfdv#sdsf%sdfs" . $setting_youngcart;
	if($sort == "A"){
		$version = $gnu_config['webview_version'];
	}else{
		$version = $gnu_config['webview_versioni'];
	}

	$array = array(
		"data" => $data_to_app1,
		"setting_board" => $setting_board,
		"version" => $version,
		"sync_data" => $sync_data,
		"sync" => $sync_data,
		"mb_id" => $mb_id_gnupush,
		"nick_name" => $nick_name,
		"setting" => $setting,
		"choose_board_s" => $gnu_config['choose_board_s'],
		"choose_board" => $gnu_config['choose_board'],
		"now_login" => $now_login,
		"social_gnu" => $social_gnu,
		"social_provider" => $social_provider,
		"profile" => $profile,
		"privacy" => $gnu_config['privacy'] ? $gnu_config['privacy'] : "none",
		"profile_link" => $profile_link,
		"member_info_val" => $member_info_val,
		"is_admin" => $gnu_is_admin,
		"category_default" => $gnu_config['category_default'],
		"use_youngcart" => $use_youngcart,
		"youngcart_category" => $youngcart_category,
		"youngcart_category_default" => $youngcart_category_default,
		"youngcart_name" => $youngcart_name,
		"iphonesound" => $iphonesound,
		"board_keyword" => $board_keyword,
		"youngcart_keyword" => $youngcart_keyword,
		"youngcart" => $youngcart,
		"badge" => strval($badge),
		"member_extra_info" => $member_extra_info,
		"chatpush" => $chatpush,
		"level" => strval($level),
		"group" => $group,
		"point" => number_format($point),
		"exp" => number_format($exp_val),
		"exp_per" => strval($exp_per_val),
		"exp_up" => number_format($exp_up_val),
		"dis_mem_info" => $dis_mem_info,
		"myNotifyList" => $gnu_config["mypushlist"],
		"use_vote" => $gnu_config['use_v'],
		"use_mention" => $gnu_config['use_mention'],
		"use_m" => $gnu_config['use_m'],
		"build_sort" => $gnu_config['build_sort']
		);

	$json = "";

	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;

}

exit();

?>