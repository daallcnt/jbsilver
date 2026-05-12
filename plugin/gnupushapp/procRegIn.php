<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');


$g5_path = g5_path();

$gnu_config = get_gnupushapp_config();


/*
$setting_board = getdefaultsettingboard();
$setting_default_value = getdefaultsetting_first();
*/

$reg_id = htmlspecialchars($_POST['reg_id']);
$version = htmlspecialchars($_POST['version']);
$sort = htmlspecialchars($_POST['sort']);
$phoneinfo = htmlspecialchars($_POST['phoneinfo']);
$masterpassword = htmlspecialchars($_POST['masterpassword']);
$os_version = htmlspecialchars($_POST['os_version']);

if(!$os_version){
	$os_version = 0;
	if($sort == "A"){
		$infoarray = explode("-",$phoneinfo);
        if(count($infoarray) > 0){
          
          $release_version = $infoarray[count($infoarray) - 1];
          if($release_version == "11") $os_version = 30;
          if($release_version == "10") $os_version = 29;
          if($release_version == "9") $os_version = 28;
          if($release_version == "8.1") $os_version = 27;
          if($release_version == "8.0" || $release_version == "8") $os_version = 26;
          if($release_version == "7.1") $os_version = 25;
          if($release_version == "7.0" || $release_version == "7") $os_version = 24;
          if($release_version == "6.0" || $release_version == "6") $os_version = 23;
          if($release_version == "5.1") $os_version = 22;
          if($release_version == "5.0" || $release_version == "5") $os_version = 21;
          if($release_version == "4.4W") $os_version = 20;
          if($release_version == "4.4") $os_version = 19;
          if($release_version == "4.3") $os_version = 18;
          if($release_version == "4.2") $os_version = 17;
          if($release_version == "4.1") $os_version = 16;
          if($release_version == "4.0.3") $os_version = 15;
		  if($release_version == "4.0" || $release_version == "4") $os_version = 14;
		}
	}

	if($sort == "I"){
		$infoarray = explode("-v",$phoneinfo);
        if(count($infoarray) > 0){
		  $os_version = $infoarray[count($infoarray) - 1];
		}
	}
}

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$b_sort = false;

if($sort == 'A' || $sort == 'I')
{
	$b_sort = true;
}

if($str_mp != $masterpassword)
{
	$b_sort = false;
}

$mb_id_gnupush= "0";
$nick_name = "none";
$member_srl = "0";
$sync = "N";
$badge = 0;
$session_ok = "false";
$now_login = "false";
$use_profile = "false";
$profile_link = "none";

if($b_sort && $reg_id)
{
	$gnu_is_admin = "N";
	$use_social = "false";
	$is_social = "false";
	$social_provider = "none";
	$social_auth_url = "none";

	if(count($gnu_config["social"]) > 0){

		$use_social = "true";

		$ii = 0;

		foreach($gnu_config["social"] as $val)
		{
			if($ii == 5)
			{
				continue;
			}
			$str_social = "social_".$val;
			if($social_auth_url == "none")
			{
				$auth = stripslashes($gnu_config[$str_social]);
				$social_auth_url = $val . "#&#" . $auth;
			}
			else
			{
				$auth = stripslashes($gnu_config[$str_social]);
				$social_auth_url .= "/-" . $val . "#&#" . $auth;
			}
			$ii++;
		}

	}

	set_session('reg_id', $reg_id);

	$row_1 = get_device_info_by_regid($reg_id);

	if($row_1)
	{
		$sync = $row_1['gpr_sync'];
		$mb_id_gnupush = $row_1['gpr_mb_id'];
		$setting = $row_1['gpr_setting'];
		$gpr_social = $row_1['gpr_social'];

		$nowDate = date('Y-m-d',time());
		$recentDate = date("Y-m-d", strtotime( $row_1['gpr_last_login'] ) );
		if($nowDate != $recentDate)
		{
			set_today_statistic("access", 1);
		}

		if($sync != "N" && $gnu_config["mypushlist"] == "Y")
		{
			$badge = getBadge_by_mb_id($mb_id_gnupush);
		}

		if($sync == "Y" || $sync == "D" || $sync == "S")
		{
			$member = get_member($mb_id_gnupush);
			if ($is_admin == 'super')
			{
				$gnu_is_admin = "Y";
			}
			$nick_name = $member['mb_nick'];
			$member_srl = strval($member['mb_no']);
			if($sync == "S"){
				$gpr_social_Array = explode("#$%", $gpr_social);
				$social_provider = $gpr_social_Array[0];
				if($gpr_social_Array[1] == "mb_id" && count($gpr_social_Array) > 2)
				{
					$member_so = get_member($gpr_social_Array[2]);
					$nick_name = $member_so['mb_nick'];
				}else{
					$memb_social = unserialize($gpr_social_Array[1]);
					$nick_name = $memb_social['mb_nick'];
				}
			}

			if(stristr($gpr_social, "#$%"))
			{

			}
			else if($gpr_social != "none")
			{
				$is_social = "true";
				$social_provider = $gpr_social;
			}

			$is_social = "false";

			if(!$is_member)
			{
				// //무조건 로그인 처리
				// // 회원아이디 세션 생성
				set_session('ss_mb_id', $member['mb_id']);
				// // FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
				set_session('ss_mb_key', md5($member['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

				// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함
				if(function_exists('generate_mb_key')) generate_mb_key($member);

				// 회원의 토큰키를 세션에 저장한다. /common.php 에서 해당 회원의 토큰값을 검사한다.
				if(function_exists('update_auth_session_token')) update_auth_session_token($member['mb_datetime']);

			}

			$now_login = "true";
			$mb_icon_url = get_gnu_profile_image($mb_id_gnupush);
			if($mb_icon_url){
				$use_profile = "true";
				$profile_link = $mb_icon_url;
			}

		}
		else
		{
			$gpr_social = "none";
			$mb_id_gnupush = "0";
		}

		if($sort == 'I'){

			$sql = " update g5_gnupushapp_gcmregid
					set gpr_sort = '{$sort}',
					gpr_social = '{$gpr_social}',
					gpr_phoneinfo = '{$phoneinfo}',
					gpr_version = '{$version}',
					gpr_badge = '{$badge}',
					gpr_os_version = '{$os_version}',
					gpr_last_login = '".G5_TIME_YMDHIS."'
					where gpr_reg_id = '{$reg_id}' ";
		}else{

			$sql = " update g5_gnupushapp_gcmregid
					set gpr_sort = '{$sort}',
					gpr_social = '{$gpr_social}',
					gpr_phoneinfo = '{$phoneinfo}',
					gpr_version = '{$version}',
					gpr_badge = '{$badge}',
					gpr_os_version = '{$os_version}',
					gpr_last_login = '".G5_TIME_YMDHIS."'
					where gpr_reg_id = '{$reg_id}' ";


		}
		sql_query($sql);

		sql_query("UPDATE g5_gnupushapp_subscribe set gpr_os_version = '$os_version' where gss_reg_id = '{$reg_id}' ");



	}
	else
	{
		$setting_newpost = 'N';
		$setting_newcom = 'N';
		$setting_notice = 'N';
		$setting_youngcart = 'N';
		$setting_youngcart_all = 'N';
		if($gnu_config['choose_board_s'] == "D" || $gnu_config['choose_board_s'] == "Y" || $gnu_config['choose_board_s'] == "F" || $gnu_config['choose_board_s'] == "F2" || $gnu_config['choose_board_s'] == "C") $setting_newpost = 'Y';
		if($gnu_config['choose_board_s'] == "D" || $gnu_config['choose_board_s'] == "F" || ($gnu_config['choose_board_s'] == "C" && $gnu_config["subscribe_comments"] == "Y")) $setting_newcom = 'Y';
		if(is_array($gnu_config['setting_f']) && in_array("g", $gnu_config['setting_f'])) $setting_notice = 'Y';
		if($gnu_config["youngcart_category_boolean"] == "Y") $setting_youngcart_all = 'Y';		
		if($gnu_config["use_youngcart"] == "Y") $setting_youngcart = 'Y';
		$setting_reply = 'N';
		$setting_mypost_com = 'N';
		$setting_mycom_com = 'N';
		$setting_mycom_tail = 'N';
		$setting_message = 'N';
		$setting_mention = 'N';
		$setting_recommendation = 'N';
		$setting_marketing = 'N';
		$setting_chat = 'Y';

		$badgeN = $badge = 0;
		$iphonesound = "sound";
		$board_keyword = "";
		$youngcart_keyword = "";
		$other_setting_array = array($iphonesound,$board_keyword,$youngcart_keyword);
		$osa = serialize($other_setting_array);

		sql_query(" INSERT INTO g5_gnupushapp_gcmregid 
					set gpr_sort = '{$sort}',
					gpr_social = 'none',
					gpr_phoneinfo = '{$phoneinfo}',
					gpr_version = '{$version}',
					gpr_last_login = '".G5_TIME_YMDHIS."',
					gpr_regdate = '".G5_TIME_YMDHIS."',
					gpr_reg_id = '{$reg_id}',
					gpr_badge = '{$badgeN}',
					gpr_other_setting = '{$osa}',
					gpr_sync = 'N',
					gpr_setting_newpost = '{$setting_newpost}',
					gpr_setting_newcom = '{$setting_newcom}',
					gpr_setting_myreply = '{$setting_reply}',
					gpr_setting_mypost_com = '{$setting_mypost_com}',
					gpr_setting_mycom_com = '{$setting_mycom_com}',
					gpr_setting_mycom_tail = '{$setting_mycom_tail}',
					gpr_setting_notice = '{$setting_notice}',
					gpr_setting_message = '{$setting_message}',
					gpr_setting_mention = '{$setting_mention}',
					gpr_setting_recommendation = '{$setting_recommendation}',
					gpr_setting_marketing = '{$setting_marketing}',
					gpr_setting_youngcart = '{$setting_youngcart}',
					gpr_setting_chat = '{$setting_chat}',
					gpr_os_version = '{$os_version}',
					gpr_setting_youngcart_all = '{$setting_youngcart_all}'
					", true);

		//구독 게시판 초기값 설정
		setdefaultsettingboard($reg_id, $sort);

		set_today_statistic("new" , 1);
	}

	set_session('gnu_badge', $badge);

	if($sort == "A"){

		$notev = $gnu_config['notev'];

		$link = "https://play.google.com/store/apps/details?id=" . $gnu_config['package'];

		if($gnu_config['webview_version'] == "")
		{
			$version = "none";
		}
		else
		{
			if($gnu_config['notev'] == "Y" || $gnu_config['notev'] == "J")
			{
				$version = $gnu_config['webview_version'];
			}
		}

	}else{
		if($gnu_config['linki']){
			$link = "itms-apps://itunes.apple.com/kr/app/apple-store/id" . $gnu_config['linki'] . "?mt=8";
		}else{
			$link = "none";
		}

		$version = $gnu_config['webview_versioni'];
		$notev = $gnu_config['notevi'];
	}

	$use_profile = "false";
	$profile_link = "none";

	$table_board_list = "";

	$sql = " select * from {$g5['board_table']}";
	$result = sql_query($sql);
	unset($row);
	for ($i=0; $row=sql_fetch_array($result); $i++) {

		if($gnu_config['quick_module_srls'])
		{

			if(!(is_array($gnu_config['quick_module_srls']) && in_array($row['bo_table'], $gnu_config['quick_module_srls'])))
			{
				if($table_board_list == "")
				{
					$table_board_list = $row['bo_subject'] . "asdfef###sefesf" . $row['bo_table'];
				}
				else
				{
					$table_board_list = $table_board_list . "feiwfiw###ofjklfs" . $row['bo_subject'] . "asdfef###sefesf" . $row['bo_table'];
				}
			}


		}
		else
		{
			if($table_board_list == "")
			{
				$table_board_list = $row['bo_subject'] . "asdfef###sefesf" . $row['bo_table'];
			}
			else
			{
				$table_board_list = $table_board_list . "feiwfiw###ofjklfs" . $row['bo_subject'] . "asdfef###sefesf" . $row['bo_table'];
			}

		}
	}

	if($table_board_list == "")
	{

		$table_board_list = "none";

	}

	$login_sort = $gnu_config['login_method'];

	if($gnu_config['notadmob_url'] == "")
	{
		$gnu_config['notadmob_url'] = "nonesdfsdf,noneaefv";
	}
	if($gnu_config['notbottom_url'] == "")
	{
		$gnu_config['notbottom_url'] = "nonesdfsdf,noneaefv";
	}

	if($gnu_config['banner_admob'] == "N")
	{
		$gnu_config['notadmob_url'] = "%#$" . "all%#$";
	}

	if($gnu_config['notnw_url'] == "")
	{
		$gnu_config['notnw_url'] = "none";
	}

	// if($session_ok = get_session('pushappfirstA')) {
	// 	if($session_ok != "true")
	// 	{
	// 		$session_ok = "false";
	// 	}
	// }

	$session_ok = "true";

	$quick_default_a = "true";
	$quick_default_b = "true";
	$quick_default_c = "true";
	$quick_default_d = "true";
	$quick_default_e = "true";

	if(!(is_array($gnu_config['quick_default']) && in_array("a", $gnu_config['quick_default'])))
	{
		$quick_default_a = "false";
	}

	if(!(is_array($gnu_config['quick_default']) && in_array("b", $gnu_config['quick_default'])))
	{
		$quick_default_b = "false";
	}

	if(!(is_array($gnu_config['quick_default']) && in_array("c", $gnu_config['quick_default'])))
	{
		$quick_default_c = "false";
	}

	if(!(is_array($gnu_config['quick_default']) && in_array("d", $gnu_config['quick_default'])))
	{
		$quick_default_d = "false";
	}

	if(!(is_array($gnu_config['quick_default']) && in_array("e", $gnu_config['quick_default'])))
	{
		$quick_default_e = "false";
	}


	$quick_p = $gnu_config['quick_p'];
	if($gnu_config['quick_b_c'] == "" || $gnu_config['quick_b_cc'] == "")
	{
		$quick_b_c = "none";
		$quick_b_cc = "none";
	}
	else
	{
		$quick_b_c = $gnu_config['quick_b_c'];
		$quick_b_cc = $gnu_config['quick_b_cc'];
	}

	$quick = "none";
	$quick_login = "none";
	if($gnu_config['quick1_icon'] == "")
	{
		$gnu_config['quick1_icon'] = "none";
	}
	if($gnu_config['quick2_icon'] == "")
	{
		$gnu_config['quick2_icon'] = "none";
	}
	if($gnu_config['quick3_icon'] == "")
	{
		$gnu_config['quick3_icon'] = "none";
	}
	if($gnu_config['quick4_icon'] == "")
	{
		$gnu_config['quick4_icon'] = "none";
	}
	if($gnu_config['quick5_icon'] == "")
	{
		$gnu_config['quick5_icon'] = "none";
	}

	if($gnu_config['quick1_color'] == "")
	{
		$gnu_config['quick1_color'] = "none";
	}
	if($gnu_config['quick2_color'] == "")
	{
		$gnu_config['quick2_color'] = "none";
	}
	if($gnu_config['quick3_color'] == "")
	{
		$gnu_config['quick3_color'] = "none";
	}
	if($gnu_config['quick4_color'] == "")
	{
		$gnu_config['quick4_color'] = "none";
	}
	if($gnu_config['quick5_color'] == "")
	{
		$gnu_config['quick5_color'] = "none";
	}

	if($gnu_config['quick_login'] != "" && $gnu_config['quick_login_link'] != "")
	{
		$quick_login = $gnu_config['quick_login'] . "weifjoaefikafjf" . $gnu_config['quick_login_link'] . "weifjoaefikafjf" . $gnu_config['quick_login_icon'] . "weifjoaefikafjf" . $gnu_config['quick_login_color'];
	}

	$quick_login = stripslashes($quick_login);

	if($gnu_config['quick1'] != "" && $gnu_config['quick1_link'] != "")
	{
		$quick = $gnu_config['quick1'] . "weifjoaefikafjf" . $gnu_config['quick1_link'] . "weifjoaefikafjf" . $gnu_config['quick1_icon'] . "weifjoaefikafjf" . $gnu_config['quick1_color'];
	}
	if($gnu_config['quick2'] != "" && $gnu_config['quick2_link'] != "")
	{
		if($quick == "none")
		{
			$quick = $gnu_config['quick2'] . "weifjoaefikafjf" . $gnu_config['quick2_link'] . "weifjoaefikafjf" . $gnu_config['quick2_icon'] . "weifjoaefikafjf" . $gnu_config['quick2_color'];
		}
		else
		{
			$quick = $quick . "sdfsdfdsffsdd" . $gnu_config['quick2'] . "weifjoaefikafjf" . $gnu_config['quick2_link'] . "weifjoaefikafjf" . $gnu_config['quick2_icon'] . "weifjoaefikafjf" . $gnu_config['quick2_color'];
		}
	}
	if($gnu_config['quick3'] != "" && $gnu_config['quick3_link'] != "")
	{
		if($quick == "none")
		{
			$quick = $gnu_config['quick3'] . "weifjoaefikafjf" . $gnu_config['quick3_link'] . "weifjoaefikafjf" . $gnu_config['quick3_icon'] . "weifjoaefikafjf" . $gnu_config['quick3_color'];
		}
		else
		{
			$quick = $quick . "sdfsdfdsffsdd" . $gnu_config['quick3'] . "weifjoaefikafjf" . $gnu_config['quick3_link'] . "weifjoaefikafjf" . $gnu_config['quick3_icon'] . "weifjoaefikafjf" . $gnu_config['quick3_color'];
		}
	}
	if($gnu_config['quick4'] != "" && $gnu_config['quick4_link'] != "")
	{
		if($quick == "none")
		{
			$quick = $gnu_config['quick4'] . "weifjoaefikafjf" . $gnu_config['quick4_link'] . "weifjoaefikafjf" . $gnu_config['quick4_icon'] . "weifjoaefikafjf" . $gnu_config['quick4_color'];
		}
		else
		{
			$quick = $quick . "sdfsdfdsffsdd" . $gnu_config['quick4'] . "weifjoaefikafjf" . $gnu_config['quick4_link'] . "weifjoaefikafjf" . $gnu_config['quick4_icon'] . "weifjoaefikafjf" . $gnu_config['quick4_color'];
		}
	}
	if($gnu_config['quick5'] != "" && $gnu_config['quick5_link'] != "")
	{
		if($quick == "none")
		{
			$quick = $gnu_config['quick5'] . "weifjoaefikafjf" . $gnu_config['quick5_link'] . "weifjoaefikafjf" . $gnu_config['quick5_icon'] . "weifjoaefikafjf" . $gnu_config['quick5_color'];
		}
		else
		{
			$quick = $quick . "sdfsdfdsffsdd" . $gnu_config['quick5'] . "weifjoaefikafjf" . $gnu_config['quick5_link'] . "weifjoaefikafjf" . $gnu_config['quick5_icon'] . "weifjoaefikafjf" . $gnu_config['quick5_color'];
		}
	}

	$bottom_menu = "none";
	$bottom_menu_other = "none";

	if($sort == 'I')
	{
		if($gnu_config['bottom_menu_stylei'] == 'I')
		{

			$bottom_menu = $gnu_config['bottom_menu1i'] . "weifjoaefikafjf" . $gnu_config['bottom_menu1_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menu1_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menu1_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu2i'] . "weifjoaefikafjf" . $gnu_config['bottom_menu2_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menu2_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menu2_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu3i'] . "weifjoaefikafjf" . $gnu_config['bottom_menu3_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menu3_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menu3_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu4i'] . "weifjoaefikafjf" . $gnu_config['bottom_menu4_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menu4_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menu4_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu5i'] . "weifjoaefikafjf" . $gnu_config['bottom_menu5_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menu5_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menu5_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu6i'] . "weifjoaefikafjf" . $gnu_config['bottom_menu6_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menu6_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menu6_colori'];

		}
		else
		{

			$bottom_menu = $gnu_config['bottom_menuc1_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc1_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc1_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc2_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc2_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc2_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc3_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc3_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc3_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc4_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc4_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc4_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc5_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc5_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc5_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc6_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc6_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc6_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc7_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc7_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc7_colori'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc8_linki'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc8_iconi'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc8_colori'];


		}

	}
	else
	{

		if($gnu_config['bottom_menu_style'] == 'I')
		{
			$bottom_menu = $gnu_config['bottom_menu1'] . "weifjoaefikafjf" . $gnu_config['bottom_menu1_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menu1_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menu1_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu2'] . "weifjoaefikafjf" . $gnu_config['bottom_menu2_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menu2_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menu2_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu3'] . "weifjoaefikafjf" . $gnu_config['bottom_menu3_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menu3_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menu3_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu4'] . "weifjoaefikafjf" . $gnu_config['bottom_menu4_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menu4_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menu4_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu5'] . "weifjoaefikafjf" . $gnu_config['bottom_menu5_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menu5_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menu5_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menu6'] . "weifjoaefikafjf" . $gnu_config['bottom_menu6_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menu6_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menu6_color'];
		}
		else
		{
			$bottom_menu = $gnu_config['bottom_menuc1_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc1_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc1_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc2_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc2_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc2_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc3_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc3_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc3_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc4_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc4_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc4_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc5_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc5_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc5_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc6_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc6_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc6_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc7_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc7_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc7_color'];
			$bottom_menu = $bottom_menu . "sdfsdfdsffsdd" . $gnu_config['bottom_menuc8_link'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc8_icon'] . "weifjoaefikafjf" . $gnu_config['bottom_menuc8_color'];

		}

	}

	

	if($gnu_config['use_quick'] == "Y")
	{
		if($quick_default_a == 'false' && $quick_default_b == 'false' && $quick_default_c == 'false' && $quick_default_d == 'false' && $quick_default_e == 'false' && $quick == 'none')
		{
			$use_quick = "false";
		}
		else
		{
			$use_quick = "true";
		}
	}
	else
	{
		$use_quick = "false";
	}

	if($gnu_config['lock'] == "Y")
	{
		$lock_second = $gnu_config['lock_second'];
	}
	else
	{
		$lock_second = "10";
	}

	if($gnu_config['first_page'] == "Y")
	{
		$first_page_url = $g5_path['url']."/";
	}
	else
	{
		$first_page_url = $gnu_config['first_page_url'];
	}

	if($gnu_config['login_first_page'] == "N")
	{
		$login_first_page_url = $g5_path['url']."/";
	}
	else
	{
		$login_first_page_url = $gnu_config['login_first_page_url'];
	}

	if($gnu_config['home_page'] == "Y")
	{
		$home_page_url = $g5_path['url']."/";
	}
	else
	{
		$home_page_url = $gnu_config['home_page_url'];
	}

	$social_callback = "none";

	if($gnu_config['social_callback'])
	{
		$social_callback = $gnu_config['social_callback'];
	}

	$intro_s = strval($gnu_config['intro_s']);
	$badge = strval($badge);

	if($gnu_config['eventimage0_filename'] == "")
	{
		$event0 = "none";
		$eventlink0 = "none";
	}
	else
	{
		$event0 = $gnu_config['eventimage0_filename'];
		$eventlink0 = $gnu_config['eventlink0'];
	}

	if($gnu_config['eventimage1_filename'] == "")
	{
		$event1 = "none";
		$eventlink1 = "none";
	}
	else
	{
		$event1 = $gnu_config['eventimage1_filename'];
		$eventlink1 = $gnu_config['eventlink1'];
	}

	if($gnu_config['eventimage2_filename'] == "")
	{
		$event2 = "none";
		$eventlink2 = "none";
	}
	else
	{
		$event2 = $gnu_config['eventimage2_filename'];
		$eventlink2 = $gnu_config['eventlink2'];
	}

	if($gnu_config['eventimage3_filename'] == "")
	{
		$event3 = "none";
		$eventlink3 = "none";
	}
	else
	{
		$event3 = $gnu_config['eventimage3_filename'];
		$eventlink3 = $gnu_config['eventlink3'];
	}

	if($gnu_config['eventimage4_filename'] == "")
	{
		$event4 = "none";
		$eventlink4 = "none";
	}
	else
	{
		$event4 = $gnu_config['eventimage4_filename'];
		$eventlink4 = $gnu_config['eventlink4'];
	}

	if(!$gnu_config['privacy']){
		$privacy = "none";
	}else{
		$privacy = $gnu_config['privacy'];
	}

	// android Oreo부터 적용되는 푸시알림 채널 셋팅
	// $channel1 = array(채널id, 채널이름, 알림우선순위 -> MAX/HIGH/DEFAULT/NONE/LOW/MIN, 푸시채널에 대한 설명, LED 라이트 사용 여부, LED색깔 -> #00ff00(녹색)/#0000ff(파란색)/#ff0000(빨강색), 진동여부, 진동반복간격(-로 구분, 1000 = 1초), 푸시표시방법 -> PUBLIC/PRIVATE/SECRET, 기존채널을 이설정으로 재설정 여부);
	// 알림우선순위는 무조건 HIGH로 설정해주세요. 푸시표시방식은 lockscreen상황에서 푸시표시방식입니다. PUBLIC은 푸시알림이 표시되고 알림의 모든 내용을 보여줍니다. PRIVATE는 알림을 보여주지만 내용을 제한적으로 보여줍니다. SECRET은 아예 표시하지 않습니다.
	$channel1 = array("important","기본알림","HIGH","내글에 달린 댓글이나 공지사항같은 중요한 내용에 대한 알림입니다.","true","#00ff00","true","500","PUBLIC","false");
	$channel2 = array("normal","구독알림","HIGH","구독한 게시판의 글이나 댓글에 대한 알림으로 중요도가 떨어지는 알림입니다.","true","#00ff00","true","300","PUBLIC","false");

	$channel_im1 = implode("@!",$channel1);
	$channel_im2 = implode("@!",$channel2);

	$channel = $channel_im1 . "ab&#ba" . $channel_im2;

	if($gnu_config['use_bottom'] == "Y")
	{
		if($gnu_config['notbottom_url'] == "%#$"."all%#$")
		{
			$gnu_config['notbottom_url'] = "";
		}
	}
	else
	{
		$gnu_config['notbottom_url'] = "%#$"."all%#$";
	}

	$array = array(
		"sync" => $sync,
		"version" => $version,
		"link" => $link,
		"use_social" => $use_social,
		"mb_id" => $mb_id_gnupush,
		"member_srl" => $member_srl,
		"notev" => $notev,
		"now_login" => $now_login,
		"is_social" => $is_social,
		"social_auth_url" => $social_auth_url,
		"use_bottom" => $gnu_config['use_bottom'],
		"onlysocial" => $gnu_config['onlysocial'],
		"choose_board" => $gnu_config['choose_board'],
		"choose_board_s" => $gnu_config['choose_board_s'],
		"use_vote" => $gnu_config['use_v'],
		"use_mention" => $gnu_config['use_mention'],
		"use_m" => $gnu_config['use_m'],
		"login_sort" => $login_sort,
		"loginpage" => $gnu_config['loginpage'],
		"notadmob_url" => $gnu_config['notadmob_url'],
		"nologin" => $gnu_config['nologin'],
		"session_ok" => $session_ok,
		"intro_s" => $intro_s,
		"quick" => $quick,
		"use_quick" => $use_quick,
		"table_board_list" => $table_board_list,
		"quick_p" => $quick_p,
		"quick_b_c" => $quick_b_c,
		"quick_b_cc" => $quick_b_cc,
		"first_page" => $gnu_config['first_page'],
		"first_page_url" => $first_page_url,
		"home_page" => $gnu_config['home_page'],
		"home_page_url" => $home_page_url,
		"social_provider" => $social_provider,
		"is_admin" => $gnu_is_admin,
		"lock" => $gnu_config['lock'],
		"lock_second" => $lock_second,
		"must_login" => $gnu_config['must_login'],
		"login_first_page" => $gnu_config['login_first_page'],
		"login_first_page_url" => $login_first_page_url,
		"notnw_url" => $gnu_config['notnw_url'],
		"nick_name" => $nick_name,
		"quick_default_a" => $quick_default_a,
		"quick_default_b" => $quick_default_b,
		"quick_default_c" => $quick_default_c,
		"quick_default_d" => $quick_default_d,
		"quick_default_e" => $quick_default_e,
		"is_loading_file" => $gnu_config['is_loading_file'],
		"loading_file_name" => $gnu_config['loading_file_name'],
		"progressbar" => $gnu_config['progressbar'],
		"loading_s" => $gnu_config['loading_s'],
		"menubutton" => $gnu_config['menubutton'],
		"push_style_bp" => $gnu_config['push_style_bp'],
		"push_duplication" => $gnu_config['push_duplication'],
		"build_sort" => $gnu_config["build_sort"],
		"choose_board_keyword" =>$gnu_config["choose_board_keyword"],
		"youngcart_keyword" => $gnu_config["youngcart_keyword"],
		"bottom_menu_style" => $gnu_config["bottom_menu_style"],
		"bottom_menu" => $bottom_menu,
		"bottom_menu_c" => $gnu_config["bottom_menu_c"],
		"notbottom_url" => $gnu_config['notbottom_url'],
		"quick_bottom_margin" => $gnu_config['quick_bottom_margin'],
		"appcache" => $gnu_config['appcache'],
		"quick_login" => $quick_login,
		"back_finish" => $gnu_config["back_finish"],
		"social_callback" => $social_callback,
		"event0" => $event0,
		"event1" => $event1,
		"event2" => $event2,
		"event3" => $event3,
		"event4" => $event4,
		"eventlink0" => $eventlink0,
		"eventlink1" => $eventlink1,
		"eventlink2" => $eventlink2,
		"eventlink3" => $eventlink3,
		"eventlink4" => $eventlink4,
		"use_kakao_link" => $gnu_config['use_kakao_link'],
		"default_push" => $gnu_config['default_push'],
		"wake_lock" => $gnu_config['wake_lock'],
		"headsup_push_style" => $gnu_config['headsup_push_style'],
		"popup_push_style" => $gnu_config['popup_push_style'],
		"interstitial_admob" => $gnu_config['interstitial_admob'],
		"nologinB" => $gnu_config['nologinB'],
		"bottom_menu_fonti" => $gnu_config['bottom_menu_fonti'],
		"bottom_menu_font" => $gnu_config['bottom_menu_font'],
		"quick_menu_font" => $gnu_config['quick_menu_font'],
		"privacy" => $privacy,
		"mypushlist" => $gnu_config['mypushlist'],
		"badge" => $badge,
		"use_marketing" => $gnu_config['marketing_push'],
		"bottom_menu_stylei" => $gnu_config["bottom_menu_stylei"],
		"bottom_menu_other" => $bottom_menu_other,
		"interstitial_admob_r" => $gnu_config["interstitial_admob_r"],
		"interstitial_admob_num" => $gnu_config["interstitial_admob_num"],
		"use_chat" => $gnu_config["use_chat"],
		"chatting_free_name" => $gnu_config["chatting_free_name"],
		"chatting_admin" => $gnu_config["chatting_admin"],
		"chatting_free" => $gnu_config["chatting_free"],
		"chatting_room_open" => $gnu_config["chatting_room_open"],
		"use_chatting_button" => $gnu_config["use_chatting_button"],
		"chatting_target" => $gnu_config["chatting_target"],
		"admin_mb_id" => $gnu_config['chatting_admin_id'],
		"chatting_nonmembers" => $gnu_config["chatting_nonmembers"],
		"use_profile" => $use_profile,
		"profile_link" => $profile_link,
		"chatting_file" => $gnu_config['chatting_file'],
		"eventlink0_term" => $gnu_config['eventlink0_term'],
		"channel" => $channel,
		"chat_message" => $gnu_config['chat_message'],
		"chatting_multiple" => $gnu_config['chatting_multiple'],
		"rewardad" => $gnu_config['rewardad'],
		"reward_limit" => $gnu_config['reward_limit'],
		"banner_admob" => $gnu_config['banner_admob'],
		"native_admob" => $gnu_config['native_admob'],
		"under_review" => $gnu_config['under_review'],
		"bypass_url" => $sort === "A" ? $gnu_config['bypass_url'] : $gnu_config['bypass_urli'],
		"under_review" => $sort === "A" ? $gnu_config['under_review'] : $gnu_config['under_reviewi'],
	);

}
else
{

	$array = array(
		"version" => "ban",
		"sync" => $sync,
		"link" => "none",
		"use_social" => "N",
		"mb_id" => "0",
		"notev" => $gnu_config['notev'],
		"now_login" => $now_login,
		"is_social" => $is_social,
		"social_auth_url" => $social_auth_url,
		"onlysocial" => $gnu_config['onlysocial'],
		"choose_board" => $gnu_config['choose_board'],
		"choose_board_s" => $gnu_config['choose_board_s'],
		"use_vote" => $gnu_config['use_v'],
		"use_mention" => $gnu_config['use_mention'],
		"use_m" => $gnu_config['use_m'],
		"login_sort" => $login_sort,
		"loginpage" => $gnu_config['loginpage'],
		"notadmob_url" => $gnu_config['notadmob_url'],
		"nologin" => $gnu_config['nologin'],
		"session_ok" => $session_ok,
		"intro_s" => $intro_s,
		"quick" => $quick,
		"use_quick" => $use_quick,
		"table_board_list" => $table_board_list,
		"quick_p" => $quick_p,
		"quick_b_c" => $quick_b_c,
		"quick_b_cc" => $quick_b_cc,
		"first_page" => $gnu_config['first_page'],
		"first_page_url" => $first_page_url,
		"home_page" => $gnu_config['home_page'],
		"home_page_url" => $home_page_url,
		"social_provider" => $social_provider,
		"is_admin" => $gnu_is_admin,
		"lock" => $gnu_config['lock'],
		"lock_second" => $lock_second,
		"must_login" => $gnu_config['must_login'],
		"login_first_page" => $gnu_config['login_first_page'],
		"login_first_page_url" => $login_first_page_url,
		"notnw_url" => $gnu_config['notnw_url'],
		"nick_name" => $nick_name,
		"quick_default_a" => $quick_default_a,
		"quick_default_b" => $quick_default_b,
		"is_loading_file" => $gnu_config['is_loading_file'],
		"loading_file_name" => $gnu_config['loading_file_name'],
		"progressbar" => $gnu_config['progressbar'], 
		"loading_s" => $gnu_config['loading_s'],
		"menubutton" => $gnu_config['menubutton'],
		"push_style_bp" => $gnu_config['push_style_bp'],
		"push_duplication" => $gnu_config['push_duplication'],
		"build_sort" => $gnu_config["build_sort"],
		"choose_board_keyword" =>$gnu_config["choose_board_keyword"],
		"youngcart_keyword" => $gnu_config["youngcart_keyword"],
		"bottom_menu_style" => $gnu_config["bottom_menu_style"],
		"bottom_menu" => $bottom_menu,
		"bottom_menu_c" => $gnu_config["bottom_menu_c"],
		"notbottom_url" => $gnu_config['notbottom_url'],
		"quick_bottom_margin" => $gnu_config['quick_bottom_margin'],
		"appcache" => $gnu_config['appcache'],
		"quick_login" => $quick_login,
		"back_finish" => $gnu_config["back_finish"],
		"social_callback" => $social_callback,
		"event0" => $event0,
		"event1" => $event1,
		"event2" => $event2,
		"event3" => $event3,
		"event4" => $event4,
		"eventlink0" => $eventlink0,
		"eventlink1" => $eventlink1,
		"eventlink2" => $eventlink2,
		"eventlink3" => $eventlink3,
		"eventlink4" => $eventlink4,
		"use_kakao_link" => $gnu_config['use_kakao_link'],
		"default_push" => $gnu_config['default_push'],
		"wake_lock" => $gnu_config['wake_lock'],
		"headsup_push_style" => $gnu_config['headsup_push_style'],
		"popup_push_style" => $gnu_config['popup_push_style'],
		"interstitial_admob" => $gnu_config['interstitial_admob'],
		"nologinB" => $gnu_config['nologinB'],
		"bottom_menu_fonti" => $gnu_config['bottom_menu_fonti'],
		"bottom_menu_font" => $gnu_config['bottom_menu_font'],
		"quick_menu_font" => $gnu_config['quick_menu_font'],
		"privacy" => $privacy,
		"mypushlist" => $gnu_config['mypushlist'],
		"badge" => $badge,
		"use_marketing" => $gnu_config['marketing_push'],
		"bottom_menu_stylei" => $gnu_config["bottom_menu_stylei"],
		"bottom_menu_other" => $bottom_menu_other,
		"interstitial_admob_r" => $gnu_config["interstitial_admob_r"],
		"interstitial_admob_num" => $gnu_config["interstitial_admob_num"],
		"use_chat" => $gnu_config["use_chat"],
		"chatting_admin" => $gnu_config["chatting_admin"],
		"chatting_free" => $gnu_config["chatting_free"],
		"chatting_room_open" => $gnu_config["chatting_room_open"],
		"use_chatting_button" => $gnu_config["use_chatting_button"],
		"chatting_target" => $gnu_config["chatting_target"],
		"admin_mb_id" => $gnu_config['chatting_admin_id'],
		"use_profile" => $use_profile,
		"profile_link" => $profile_link,
		"chatting_file" => $gnu_config['chatting_file'],
		"eventlink0_term" => $gnu_config['eventlink0_term'],
		"channel" => $channel,
		"chat_message" => $gnu_config['chat_message'],
		"chatting_multiple" => $gnu_config['chatting_multiple'],
		"rewardad" => $gnu_config['rewardad'],
		"reward_limit" => $gnu_config['reward_limit'],
		"banner_admob" => $gnu_config['banner_admob']
	);

}

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
//echo $json;

exit($json);

?>