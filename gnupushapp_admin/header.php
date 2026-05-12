<?php
include_once('../common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.', G5_URL . "/bbs/login.php");

$db_reload = false;

$query = "show columns from `{$g5['board_file_table']}` like 'bf_rstring' ";
$res = sql_fetch($query);
if (empty($res)) {
	sql_query(" ALTER TABLE {$g5['board_file_table']} ADD `bf_rstring` VARCHAR(10) NOT NULL DEFAULT 'none';", true);

    $db_reload = true;
}

if(defined('APMS_VERSION')){
	$query = "show columns from {$g5['apms_response']} like 'target_url' ";
	$res = sql_fetch($query);
	if (empty($res)) {
		sql_query(" ALTER TABLE {$g5['apms_response']} ADD `target_url` text;", true);

		$db_reload = true;
	}
}

if(defined('_EYOOM_VESION_')){
	$query = "show columns from {$g5['eyoom_respond']} like 'target_url' ";
	$res = sql_fetch($query);
	if (empty($res)) {
		sql_query(" ALTER TABLE {$g5['eyoom_respond']} ADD `target_url` text;", true);

		$db_reload = true;
	}
}
/*
$query = "show columns from {$g5['memo_table']} like 'me_chatfile' ";
$res = sql_fetch($query);
if (empty($res)) {
	sql_query(" ALTER TABLE {$g5['memo_table']} ADD `me_chatfile` varchar(50) DEFAULT 'none';", true);
	sql_query(" ALTER TABLE {$g5['memo_table']} ADD `me_chatfilename` varchar(255) DEFAULT 'none';", true);
	$db_reload = true;
}
*/


//bre_type : P(게시글), C(댓글)
//bre_status : N(현재신고상태), D(삭제), B(반려)

if(!sql_query(" DESCRIBE g5_board_report_gnu ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_board_report_gnu` (
  `bre_ix` int(11) NOT NULL AUTO_INCREMENT,
  `bre_bo_table` varchar(20) NOT NULL,
  `bre_wr_id` int(11) NOT NULL,
  `bre_type` char(1) DEFAULT 'P',
  `bre_target_mb_id` varchar(20) DEFAULT NULL,
  `bre_mb_id` varchar(20) DEFAULT NULL,
  `bre_original_text` text,
  `bre_status` char(1) DEFAULT 'N',
  `bre_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bre_confirm` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bre_ix`),
  KEY `idx_bre_status` (`bre_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}


if(!sql_query(" DESCRIBE g5_gnupushapp_subscribe ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_subscribe` (
  `gss_ix` int(11) NOT NULL AUTO_INCREMENT,
  `gss_reg_id` varchar(250) NOT NULL,
  `gss_bo_table` varchar(20) NOT NULL,
  `gss_ca_name` varchar(255) NOT NULL,
  `gss_is_youngcart` char(1) DEFAULT 'N',
  `gss_post_subscribe` char(1) DEFAULT 'N',
  `gss_post_comment_subscribe` char(1) DEFAULT 'N',
  `gss_post_subscribe_onoff` char(1) DEFAULT 'N',
  `gss_mb_id` varchar(20) DEFAULT NULL,
  `gss_setting_youngcart_all` char(1) NOT NULL DEFAULT 'N',		
  `gss_sort` char(1) NOT NULL DEFAULT 'W',
  `gss_other_setting` text,
  `gpr_os_version` float NOT NULL DEFAULT 0,
  `gss_sync` char(1) NOT NULL DEFAULT 'N',
  `gss_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`gss_ix`),
  KEY `idx_gss_reg_id` (`gss_reg_id`),
  KEY `idx_gss_bo_table` (`gss_bo_table`),
  KEY `idx_gss_ca_name` (`gss_ca_name`),
  KEY `idx_gss_is_youngcart` (`gss_is_youngcart`),
  KEY `idx_gss_post_subscribe` (`gss_post_subscribe`),
  KEY `idx_gss_post_comment_subscribe` (`gss_post_comment_subscribe`),
  KEY `idx_gpr_os_version` (`gpr_os_version`),
  KEY `idx_gss_post_subscribe_onoff` (`gss_post_subscribe_onoff`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}

if(!sql_query(" DESCRIBE g5_gnupushapp_statistics ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_statistics` (
  `grs_ix` int(11) NOT NULL AUTO_INCREMENT,
  `grs_access` int(11) NOT NULL DEFAULT '0',
  `grs_push` int(11) NOT NULL DEFAULT '0',
  `grs_new` int(11) NOT NULL DEFAULT '0',
  `grs_click` int(11) NOT NULL DEFAULT '0',
  `grs_total_device` int(11) NOT NULL DEFAULT '0',
  `grs_error` int(11) NOT NULL DEFAULT '0',
  `grs_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`grs_ix`),
  KEY `idx_grs_regdate` (`grs_regdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}

if(!sql_query(" DESCRIBE g5_gnupushapp_inapp ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_inapp` (
  `gin_ix` int(11) NOT NULL AUTO_INCREMENT,
  `gin_reg_id` varchar(250) NOT NULL,
  `gin_mb_id` varchar(20) DEFAULT NULL,
  `gin_secret` varchar(80) NOT NULL,
  `gin_type` varchar(20) NOT NULL,
  `gin_product_id` varchar(160) NOT NULL,
  `gin_status` varchar(20) NOT NULL,
  `gin_money` int(11) NOT NULL,
  `gin_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`gin_ix`),
  KEY `idx_gin_reg_id` (`gin_reg_id`),
  KEY `idx_gin_mb_id` (`gin_mb_id`),
  KEY `idx_gin_status` (`gin_status`),
  KEY `idx_gin_secret` (`gin_secret`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}


if(!sql_query(" DESCRIBE g5_gnupushapp_reward ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_reward` (
  `grr_ix` int(11) NOT NULL AUTO_INCREMENT,
  `grr_reg_id` varchar(250) NOT NULL,
  `grr_mb_id` varchar(20) DEFAULT NULL,
  `grr_secret` varchar(80) NOT NULL,
  `grr_type` varchar(10) NOT NULL,
  `grr_amount` int(11) NOT NULL,
  `grr_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`grr_ix`),
  KEY `idx_grr_reg_id` (`grr_reg_id`),
  KEY `idx_grr_mb_id` (`grr_mb_id`),
  KEY `idx_grr_secret` (`grr_secret`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}

if(!sql_query(" DESCRIBE g5_gnupushapp_notificationlist ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_notificationlist` (
  `gnl_ix` int(11) NOT NULL AUTO_INCREMENT,
  `gnl_mb_id` varchar(20) DEFAULT NULL,
  `gnl_reg_id` varchar(250) NOT NULL,
  `gnl_subject` text,
  `gnl_type` varchar(20) NOT NULL,
  `gnl_target_url` varchar(255) NOT NULL,
  `gnl_isconfirm` char(2) DEFAULT 'N',
  `gnl_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`gnl_ix`),
  KEY `idx_gnl_mb_id` (`gnl_mb_id`),
  KEY `idx_gnl_reg_id` (`gnl_reg_id`),
  KEY `idx_gnl_regdate` (`gnl_regdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}

if(!sql_query(" DESCRIBE g5_gnupushapp_filedown ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_filedown` (
  `ggf_ix` int(11) NOT NULL AUTO_INCREMENT,
  `ggf_keypass` varchar(50) NOT NULL,
  `ggf_bo_table` varchar(20) NOT NULL,
  `ggf_wr_id` int(11) NOT NULL,
  `ggf_no` int(11) NOT NULL,
  `ggf_chatOFN` varchar(40),
  `ggf_chatFN` varchar(250),
  `ggf_chat_ix` int(11),
  `ggf_downloadok` char(2) NOT NULL,
  `ggf_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ggf_ix`),
  KEY `idx_ggf_keypass` (`ggf_keypass`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}else{

	$query = "show columns from g5_gnupushapp_filedown like 'ggf_chatOFN' ";
	$res = sql_fetch($query);
	if (empty($res)) {
		sql_query(" ALTER TABLE g5_gnupushapp_filedown ADD `ggf_chatOFN` varchar(40);", true);
		sql_query(" ALTER TABLE g5_gnupushapp_filedown ADD `ggf_chatFN` varchar(250);", true);
		sql_query(" ALTER TABLE g5_gnupushapp_filedown ADD `ggf_chat_ix` int(11);", true);

		$db_reload = true;
	}

}

if(!sql_query(" DESCRIBE g5_gnupushapp_file_num ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_file_num` (
  `gf_ix` int(11) NOT NULL AUTO_INCREMENT,
  `gf_rnum` varchar(10) NOT NULL,
  PRIMARY KEY (`gf_ix`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);
	sql_query(" INSERT INTO g5_gnupushapp_file_num 
                    SET `gf_rnum` = 'nowstart'
            ", true);
    $db_reload = true;
}

if(!sql_query(" DESCRIBE g5_gnupushapp_youngcart_num ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_youngcart_num` (
  `gpy_ix` int(11) NOT NULL AUTO_INCREMENT,
  `gpy_status` char(1) NOT NULL DEFAULT 'N',
  `gpy_it_id` varchar(20) NOT NULL,
  PRIMARY KEY (`gpy_ix`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}

if(!sql_query(" DESCRIBE g5_gnupushapp_gcmregid ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_gcmregid` (
		`gpr_ix` int(11) NOT NULL AUTO_INCREMENT,
		`gpr_reg_id` varchar(250) NOT NULL,
		`gpr_mb_id` varchar(20) DEFAULT NULL,
		`gpr_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`gpr_setting_newpost` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_newcom` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_myreply` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_mypost_com` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_mycom_com` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_mycom_tail` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_notice` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_message` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_mention` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_recommendation` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_marketing` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_youngcart` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_chat` char(1) NOT NULL DEFAULT 'N',
		`gpr_setting_youngcart_all` char(1) NOT NULL DEFAULT 'N',		
		`gpr_sort` char(1) NOT NULL DEFAULT 'W',
		`gpr_last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`gpr_social` text,
		`gpr_badge` int(11) NOT NULL DEFAULT 0,
		`gpr_other_setting` text,
    `gpr_os_version` float NOT NULL DEFAULT 0,
		`gpr_sync` char(1) NOT NULL DEFAULT 'N',
		`gpr_version` varchar(20) DEFAULT 'none',
		`gpr_phoneinfo` varchar(30) DEFAULT NULL,
		PRIMARY KEY (`gpr_ix`),
		KEY `idx_gpr_regdate` (`gpr_regdate`),
		KEY `idx_gpr_reg_id` (`gpr_reg_id`),
		KEY `idx_gpr_mb_id` (`gpr_mb_id`),
		KEY `idx_gpr_setting_newpost` (`gpr_setting_newpost`),
		KEY `idx_gpr_setting_newcom` (`gpr_setting_newcom`),
		KEY `idx_gpr_setting_myreply` (`gpr_setting_myreply`),
		KEY `idx_gpr_setting_mypost_com` (`gpr_setting_mypost_com`),
		KEY `idx_gpr_setting_mycom_com` (`gpr_setting_mycom_com`),
		KEY `idx_gpr_setting_mycom_tail` (`gpr_setting_mycom_tail`),
		KEY `idx_gpr_setting_notice` (`gpr_setting_notice`),
		KEY `idx_gpr_setting_message` (`gpr_setting_message`),
		KEY `idx_gpr_setting_mention` (`gpr_setting_mention`),
		KEY `idx_gpr_setting_recommendation` (`gpr_setting_recommendation`),
		KEY `idx_gpr_setting_marketing` (`gpr_setting_marketing`),
		KEY `idx_gpr_setting_youngcart` (`gpr_setting_youngcart`),
		KEY `idx_gpr_setting_chat` (`gpr_setting_chat`),
		KEY `idx_gpr_setting_youngcart_all` (`gpr_setting_youngcart_all`),
    KEY `idx_gpr_os_version` (`gpr_os_version`),
		KEY `idx_gpr_sync` (`gpr_sync`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    $db_reload = true;
}else{
	$query = "show columns from g5_gnupushapp_gcmregid like 'gpr_badge' ";
	$res = sql_fetch($query);
	if (empty($res)) {
		sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_badge` int(11) NOT NULL DEFAULT 0;", true);
		sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_other_setting` text;", true);

		$db_reload = true;
  }

  $query = "show columns from g5_gnupushapp_gcmregid like 'gpr_os_version' ";
	$res = sql_fetch($query);
	if (empty($res)) {
    sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_os_version` float NOT NULL DEFAULT 0;", true);
    sql_query(" ALTER TABLE g5_gnupushapp_subscribe ADD `gpr_os_version` float NOT NULL DEFAULT 0;", true);
    
    //전체 phoneinfo에서 값 가져와서 넣기
    $device_result = sql_query("SELECT * from g5_gnupushapp_gcmregid where 1 ");
    for($i=0;$row=sql_fetch_array($device_result);$i++){
      if($row['gpr_sort'] == "A"){
        $infoarray = explode("-",$row['gpr_phoneinfo']);
        if(count($infoarray) > 0){
          $sdk = 0;
          $release_version = $infoarray[count($infoarray) - 1];
          if($release_version == "11") $sdk = 30;
          if($release_version == "10") $sdk = 29;
          if($release_version == "9") $sdk = 28;
          if($release_version == "8.1") $sdk = 27;
          if($release_version == "8.0" || $release_version == "8") $sdk = 26;
          if($release_version == "7.1") $sdk = 25;
          if($release_version == "7.0" || $release_version == "7") $sdk = 24;
          if($release_version == "6.0" || $release_version == "6") $sdk = 23;
          if($release_version == "5.1") $sdk = 22;
          if($release_version == "5.0" || $release_version == "5") $sdk = 21;
          if($release_version == "4.4W") $sdk = 20;
          if($release_version == "4.4") $sdk = 19;
          if($release_version == "4.3") $sdk = 18;
          if($release_version == "4.2") $sdk = 17;
          if($release_version == "4.1") $sdk = 16;
          if($release_version == "4.0.3") $sdk = 15;
          if($release_version == "4.0" || $release_version == "4") $sdk = 14;

          sql_query("UPDATE g5_gnupushapp_gcmregid set gpr_os_version = '$sdk' where gpr_ix = '{$row['gpr_ix']}' ");
          sql_query("UPDATE g5_gnupushapp_subscribe set gpr_os_version = '$sdk' where gss_reg_id = '{$row['gpr_reg_id']}' ");

        }
      }

      if($row['gpr_sort'] == "I"){
        $infoarray = explode("-v",$row['gpr_phoneinfo']);
        if(count($infoarray) > 0){
          $release_version = $infoarray[count($infoarray) - 1];
          sql_query("UPDATE g5_gnupushapp_gcmregid set gpr_os_version = '$release_version' where gpr_ix = '{$row['gpr_ix']}' ");
          sql_query("UPDATE g5_gnupushapp_subscribe set gpr_os_version = '$release_version' where gss_reg_id = '{$row['gpr_reg_id']}' ");
        }
      }
    }

		$db_reload = true;
  }
}

if(!sql_query(" DESCRIBE g5_gnupushapp_errorlog ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_errorlog` (
  `ge_ix` int(11) NOT NULL AUTO_INCREMENT,
  `ge_reg_id` varchar(250) NOT NULL,
  `ge_text` text NOT NULL,
  `ge_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ge_ix`),
  KEY `idx_ge_regdate` (`ge_regdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

if(!sql_query(" DESCRIBE g5_gnupushapp_push ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_push` (
  `gp_ix` int(11) NOT NULL AUTO_INCREMENT,
  `gp_pushid` varchar(100) NOT NULL,
  `gp_type` varchar(20) NOT NULL,
  `gp_text` text NOT NULL,
  `gp_target_browser` varchar(50) DEFAULT NULL,
  `gp_target_url` varchar(255) NOT NULL,
  `gp_target_title` varchar(255) NOT NULL,
  `gp_response` int(11) DEFAULT '0',
  `gp_push_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gp_issend` char(2) DEFAULT 'N',
  PRIMARY KEY (`gp_ix`),
  KEY `idx_gp_pushid` (`gp_pushid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}else{

	$query = "show columns from g5_gnupushapp_push like 'gp_response' ";
	$res = sql_fetch($query);
	if (empty($res)) {
		sql_query(" ALTER TABLE g5_gnupushapp_push ADD `gp_response` int(11) DEFAULT '0';", true);

		$db_reload = true;
	}

}

if(!sql_query(" DESCRIBE g5_gnupushapp_config ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `g5_gnupushapp_config` (
  `gc_ix` int(11) NOT NULL AUTO_INCREMENT,
  `gc_text` text NOT NULL,
  `gc_reg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gc_up` varchar(10) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`gc_ix`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);


	$config_gnu = array();
	$config_gnu["masterpassword"] = get_random_string_gnu('32');
	$config_gnu['use'] = 'Y';
	$config_gnu['setting_f'] = array("g");
	$config_gnu['setting_a'] = array("c", "d", "e", "f", "g", "h");
	$config_gnu['quick_default'] = array("a", "b");
	$config_gnu['mem_info'] = array("group");
	$config_gnu['sort_v'] = 'W';
	$config_gnu['change_a'] = 'Y';
	$config_gnu['change_f'] = 'Y';
	$config_gnu['onlysocial'] = 'N';
	$config_gnu['board_grant'] = 'Y';
	$config_gnu['board_grant_c'] = 'bo_read_level';
	$config_gnu['use_d'] = 'Y';
	$config_gnu['use_c'] = 'Y';
	$config_gnu['use_m'] = 'Y';
	$config_gnu['use_v'] = 'N';
	$config_gnu['use_mention'] = 'N';
	$config_gnu['push_style'] = 'Y';
	$config_gnu['push_style_m'] = 'N';
	$config_gnu['notev'] = 'J';
	$config_gnu['change_s'] = 'Y';
	$config_gnu['profile_p'] = 'Y';
	$config_gnu['google'] = 'Y';
	$config_gnu['fileorlink'] = 'Y';
	$config_gnu['vote'] = 'E';
	$config_gnu['comment_s'] = 'S';
	$config_gnu['choose_board_s'] = 'N';
	$config_gnu['board_s'] = 'S';
	$config_gnu['choose_board'] = 'Y';
	$config_gnu['push_style_bp'] = 'Y';
	$config_gnu['loginpage'] = 'Y';
	$config_gnu['menubutton'] = 'N';
	$config_gnu['nologin'] = 'Y';
	$config_gnu['intro_s'] = '2';
	$config_gnu['use_quick'] = 'Y';
	$config_gnu['quick_p'] = 'R';
	$config_gnu['first_page'] = 'Y';
	$config_gnu['home_page'] = 'Y';
	$config_gnu['login_first_page'] = 'N';
	$config_gnu['push_method'] = 'Y';
	$config_gnu['lock'] = 'N';
	$config_gnu["pushmsg"] = "N";
	$config_gnu['lock_second'] = '';
	$config_gnu["youngcart_category_default"] = "Y2";
	$config_gnu['lock_second'] = '10';
	$config_gnu['must_login'] = 'N';
	$config_gnu['under_review'] = 'N';
	$config_gnu['bypass_url'] = '';
  $config_gnu['under_reviewi'] = 'N';
	$config_gnu['bypass_urli'] = '';
	$config_gnu['use_youngcart'] = 'N';
	$config_gnu['youngcart_category'] = 'N';
	$config_gnu['youngcart_category_boolean'] = 'N';
	$config_gnu['youngcart_name'] = '쇼핑몰';
	$config_gnu['category_default'] = 'Y1';
	$config_gnu['push_div_num'] = '3000';
	$config_gnu['login_method'] = 'id';
	$config_gnu['is_loading_file'] = 'N';
	$config_gnu['progressbar'] = 'Y';
	$config_gnu['push_m'] = 'Y';
	$config_gnu['loading_s'] = '0';
	$config_gnu['list_show'] = '30';
	$config_gnu['push_duplication'] = 'Y';
	if(defined('APMS_VERSION')){
		$config_gnu['build_sort'] = 'A';
	}elseif(defined('_EYOOM_VESION_')){
		$config_gnu['build_sort'] = 'E';
	}else{
		$config_gnu['build_sort'] = 'G';
	}
	$config_gnu['choose_board_keyword'] = 'N';
	$config_gnu['youngcart_keyword'] = 'N';
	$config_gnu['quick_bottom_margin'] = '0';
	$config_gnu['back_finish'] = 'A';
	$config_gnu['use_kakao_link'] = 'N';
	$config_gnu['default_push'] = 'Y';
	$config_gnu['wake_lock'] = 'Y';
	$config_gnu['headsup_push_style'] = 'Y';
	$config_gnu['popup_push_style'] = 'N';
	$config_gnu['interstitial_admob'] = 'Y';
	$config_gnu['banner_admob'] = 'Y';
	$config_gnu['webview_version'] = '1(1.0)';
	$config_gnu['webview_versioni'] = '1(1.0)';
	$config_gnu['notevi'] = 'N';
	$config_gnu['banner_admob'] = 'Y';
	$config_gnu['setting_dp'] = 'default';
	$sql_headsup = " select * from {$g5['board_table']}";
	$headsup_module_srls = array();
	$result_headsup = sql_query($sql_headsup);
	for ($i=0; $row_headsup=sql_fetch_array($result_headsup); $i++) {
		array_push($headsup_module_srls, $row_headsup['bo_table']);
	}
	$config_gnu['headsup_module_srls'] = $headsup_module_srls;
	$config_gnu['appcache'] = get_random_string_gnu('10');

	$config_gnu_json = base64_encode(serialize($config_gnu));

	sql_query(" INSERT INTO g5_gnupushapp_config 
                    SET `gc_ix`         = 1, 
                        `gc_text`   = '".$config_gnu_json."', 
                        `gc_reg_date`   = '".G5_TIME_YMDHIS."'
            ", true);

    $db_reload = true;
}

if ($db_reload) { 
    alert("DB를 갱신합니다.", G5_URL.'/gnupushapp_admin/index.php'); 
}

$gnu_config = get_gnupushapp_config();
$gnu_config = stripslashes_deep($gnu_config);

$gnu_config["masterpassword2"] = get_random_string_gnu('32');

if(defined('APMS_VERSION')) $gnu_config["build_sort"] = "A";
if(defined('_EYOOM_VESION_')) $gnu_config["build_sort"] = "E";

$appcache_new = get_random_string_gnu('10');

if(!$gnu_config['appcache']){
	$gnu_config['appcache'] = get_random_string_gnu('10');
	$config_json = base64_encode(serialize($gnu_config));

	$sql = " update g5_gnupushapp_config
            set gc_text            = '{$config_json}',
                gc_reg_date           = '".G5_TIME_YMDHIS."'
                where gc_ix = '1'
            ";
	sql_query($sql);
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GNU푸시앱 | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="dist/adminlte.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
  <script src="bower_components/morris.js/morris.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.js"></script>
  <script src="bower_components/morris.js/lib/example.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css">
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <script src="<?php echo G5_JS_URL ?>/jquery.menu.js?ver=<?php echo G5_JS_VER; ?>"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.0/icheck-bootstrap.min.css" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119561710-1"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-119561710-1');
</script>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>G</b>NU</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>GNU</b>푸시앱</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
	  <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="messages-menu">
            <a href="<?php echo G5_URL; ?>/adm/">
              <i class="fa fa-gears"></i> 그누보드 관리자
            </a>
          </li>
		</ul>
	  </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
		<li <?php if(preg_match("/index.php/", $_SERVER["PHP_SELF"])){ ?>class="active"<?php } ?>>
          <a href="index.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="treeview <?php if(preg_match("/setting_/", $_SERVER["PHP_SELF"])){ ?>active<?php } ?>">
          <a href="setting_basic.php">
            <i class="fa fa-cogs"></i> <span>환경설정</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="setting_basic.php"> 기본설정</a></li>
			<li><a href="setting_app_basic.php"> 앱기본설정</a></li>
			<li><a href="setting_nb.php"> 앱설정화면</a></li>
			<li><a href="setting_push.php"> 푸시동작설정</a></li>
			<li><a href="setting_bottom.php"> 하단메뉴설정</a></li>
			<li><a href="setting_quick.php"> 퀵메뉴설정</a></li>
            <li><a href="setting_board.php"> 푸시게시판설정</a></li>
			<li><a href="setting_etc.php"> 팝업/채팅/애드몹</a></li>
          </ul>
        </li>
		<li <?php if(preg_match("/device_list.php/", $_SERVER["PHP_SELF"])){ ?>class="active"<?php } ?>>
          <a href="device_list.php">
            <i class="fa fa-mobile"></i> <span>등록기기목록</span>
          </a>
        </li>
		<li <?php if(preg_match("/push_group.php/", $_SERVER["PHP_SELF"])){ ?>class="active"<?php } ?>>
          <a href="push_group.php">
            <i class="fa fa-bolt"></i> <span>그룹별푸시알림</span>
          </a>
        </li>
		<li <?php if(preg_match("/push_result.php/", $_SERVER["PHP_SELF"])){ ?>class="active"<?php } ?>>
          <a href="push_result.php">
            <i class="fa fa-list-alt"></i> <span>푸시결과목록</span>
          </a>
        </li>
		<?php if($gnu_config['inapp_admin'] == "Y"){ ?>
		<li <?php if(preg_match("/inapp.php/", $_SERVER["PHP_SELF"])){ ?>class="active"<?php } ?>>
          <a href="inapp.php">
            <i class="fa fa-money"></i> <span>인앱결제</span>
          </a>
        </li>
		<?php } ?>
		<?php if($gnu_config['reward_admin'] == "Y"){ ?>
		<li <?php if(preg_match("/reward.php/", $_SERVER["PHP_SELF"])){ ?>class="active"<?php } ?>>
          <a href="reward.php">
            <i class="fa fa-money"></i> <span>리워드</span>
          </a>
        </li>
		<?php } ?>
		<li <?php if(preg_match("/error.php/", $_SERVER["PHP_SELF"])){ ?>class="active"<?php } ?>>
          <a href="error.php">
            <i class="fa fa-exclamation-triangle"></i> <span>에러로그</span>
          </a>
        </li>
		<?php if($gnu_config['use_report_gnu'] == "Y"){ ?>
		<li <?php if(preg_match("/report_result.php/", $_SERVER["PHP_SELF"])){ ?>class="active"<?php } ?>>
          <a href="report_result.php">
            <i class="fa fa-exclamation-triangle"></i> <span>게시글신고내역</span>
          </a>
        </li>
		<?php } ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>