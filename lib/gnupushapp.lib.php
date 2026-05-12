<?php
if (!defined('_GNUBOARD_')) exit;

function get_subscribe_device_push_page($setting, $setting_board_table, $setting_ca_name, $board_grant_c, $m_page, $push_div_num, $func = "none")
{
	global $g5;
	$gnu_config = get_gnupushapp_config();

	$limit = 0 + (($m_page-1)*$push_div_num);
	$sql_limit = "order by gss_ix desc limit $limit, $push_div_num ";

	$sql_setting = "gss_is_youngcart = 'N' and gss_post_subscribe_onoff = 'Y' and gss_bo_table = '{$setting_board_table}'";

	if($setting == "newpost") $sql_setting = $sql_setting . " and gss_post_subscribe = 'Y'";
	if($setting == "newcom") $sql_setting = $sql_setting . " and gss_post_comment_subscribe = 'Y'";

	if($setting_ca_name != "") $sql_setting = $sql_setting . " and gss_ca_name = '{$setting_ca_name}'";

	if($gnu_config['build_sort'] == "A"){
		if($func != "counsel" && $gnu_config['board_grant'] == 'N'){
			$sql_ex = "";
			$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting $sql_limit";
		}else{
			$sql = " select * from {$g5['board_table']} where bo_table = '{$setting_board_table}'";
			$row = sql_fetch($sql);

			if($row['as_min'] != 0 && $row['as_max'] != 0){
				$sql_ex = "as_level >= {$row['as_min']} and as_level <= {$row['as_max']} ";
			}elseif($row['as_grade'] > 1 || $row['as_equal'] != 0){
				if($row['as_equal'] == 0){
					$sql_ex = "mb_level >= {$row['as_grade']} ";
				}else{
					$sql_ex = "mb_level = {$row['as_grade']} ";
				}
			}else{
				$sql_ex = "mb_level >= {$board_grant_c} ";
			}
			$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting and gss_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex ) $sql_limit";
		}

	}else{

		if(($func != "counsel" && $gnu_config['board_grant'] == 'N') || $board_grant_c == 1)
		{
			$sql_ex = "";
			$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting $sql_limit";
		}
		else
		{
			$sql_ex = "mb_level >= {$board_grant_c} ";
			$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting and gss_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex ) $sql_limit";
		}
	}
	$devices_select = sql_query($query);
	return $devices_select;	

}

function get_subscribe_device_push($type, $setting, $setting_board_table, $setting_ca_name, $board_grant_c, $func = "none", $include_mb_id = "")
{

	global $g5;
	$gnu_config = get_gnupushapp_config();

	$sql_setting = "gss_is_youngcart = 'N' and gss_post_subscribe_onoff = 'Y' and gss_bo_table = '{$setting_board_table}'";

	if($setting == "newpost") $sql_setting = $sql_setting . " and gss_post_subscribe = 'Y'";
	if($setting == "newcom") $sql_setting = $sql_setting . " and gss_post_comment_subscribe = 'Y'";

	if($setting_ca_name != "") $sql_setting = $sql_setting . " and gss_ca_name = '{$setting_ca_name}'";

	if($gnu_config['build_sort'] == "A"){
		if($func != "secret" && $func != "counsel" && $gnu_config['board_grant'] == 'N'){
			if($type == 'count')
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where $sql_setting ";
				$row = sql_fetch($query);
				return $row['cnt'];
			}
			else if($type == 'array')
			{
				$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting ";
				$devices_select = sql_query($query);
				return $devices_select;
			}
			else
			{
				return false;
			}
		}else{

			$sql = " select * from {$g5['board_table']} where bo_table = '{$setting_board_table}'";
			$row_board = sql_fetch($sql);

			$sql_ex = "none";

			if($row_board['as_min'] != 0 && $row_board['as_max'] != 0){
				if($include_mb_id != ""){
					$sql_ex = "(as_level >= {$row_board['as_min']} and as_level <= {$row_board['as_max']}) or mb_id in ({$include_mb_id})";
				}else{
					$sql_ex = "as_level >= {$row_board['as_min']} and as_level <= {$row_board['as_max']} ";
				}
				
			}elseif($row_board['as_grade'] > 1 || $row_board['as_equal'] != 0){
				if($row_board['as_equal'] == 0){

					if($include_mb_id != ""){
						$sql_ex = "mb_level >= {$row_board['as_grade']} or mb_id in ({$include_mb_id})";
					}else{
						$sql_ex = "mb_level >= {$row_board['as_grade']} ";
					}
				}else{
					if($include_mb_id != ""){
						$sql_ex = "mb_level = {$row_board['as_grade']} or mb_id in ({$include_mb_id}) ";
					}else{
						$sql_ex = "mb_level = {$row_board['as_grade']} ";
					}
				}
			}else{
				if($board_grant_c != 1){
					if($include_mb_id != ""){
						$sql_ex = "mb_level >= {$board_grant_c} or mb_id in ({$include_mb_id})";
					}else{
						$sql_ex = "mb_level >= {$board_grant_c} ";
					}
				}
			}

			if($sql_ex == "none")
			{
				if($type == 'count')
				{
					if($include_mb_id != ""){
						$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where $sql_setting and gss_mb_id in ({$include_mb_id})";
					}else{
						$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where $sql_setting ";
					}
					$row = sql_fetch($query);
					return $row['cnt'];
				}
				else if($type == 'array')
				{
					if($include_mb_id != ""){
						$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting and gss_mb_id in ({$include_mb_id})";
					}else{
						$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting ";
					}
					$devices_select = sql_query($query);
					return $devices_select;
				}
				else
				{
					return false;
				}


			}else{

				if($type == 'count')
				{
					$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where $sql_setting and gss_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex) ";
					$row = sql_fetch($query);
					return $row['cnt'];
				}
				else if($type == 'array')
				{
					$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting and gss_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex) ";
					$devices_select = sql_query($query);
					return $devices_select;
				}
				else
				{
					return false;
				}


			}

			
		}

	}else{

		if(($func != "secret" && $func != "counsel" && $gnu_config['board_grant'] == 'N') || $board_grant_c == 1)
		{
			if($type == 'count')
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where $sql_setting ";
				$row = sql_fetch($query);
				return $row['cnt'];
			}
			else if($type == 'array')
			{
				$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting ";
				$devices_select = sql_query($query);
				return $devices_select;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if($include_mb_id != ""){
				$sql_ex = "mb_level >= {$board_grant_c} or mb_id in ({$include_mb_id}) ";
			}else{
				$sql_ex = "mb_level >= {$board_grant_c} ";
			}

			if($type == 'count')
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_subscribe where $sql_setting and gss_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex)";
				$row = sql_fetch($query);
				return $row['cnt'];
			}
			else if($type == 'array')
			{
				$query = "select gss_reg_id,gss_mb_id,gss_sort,gss_other_setting,gss_sync,gpr_os_version from g5_gnupushapp_subscribe where $sql_setting and gss_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex)";
				$devices_select = sql_query($query);
				return $devices_select;
			}
			else
			{
				return false;
			}
		}
	}
}

function get_devices_push_page($setting, $board_grant_c, $setting_board_table, $m_page, $push_div_num, $func = "none")
{
	global $g5;
	$gnu_config = get_gnupushapp_config();
	$limit = 0 + (($m_page-1)*$push_div_num);
	$sql_limit = "order by gpr_ix desc limit $limit, $push_div_num ";
	$sql_setting = "1";

	if($gnu_config['build_sort'] == "A"){
		if($func != "counsel" && $gnu_config['board_grant'] == 'N'){
			if($setting == "notice") $sql_setting = $sql_setting . " and gpr_setting_notice = 'Y'";
			$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting $sql_limit";
		}else{
			$sql = " select * from {$g5['board_table']} where bo_table = '{$setting_board_table}'";
			$row = sql_fetch($sql);

			$sql_ex = "none";

			if($row['as_min'] != 0 && $row['as_max'] != 0){
				$sql_ex = "as_level >= {$row['as_min']} and as_level <= {$row['as_max']} ";
			}elseif($row['as_grade'] > 1 || $row['as_equal'] != 0){
				if($row['as_equal'] == 0){
					$sql_ex = "mb_level >= {$row['as_grade']} ";
				}else{
					$sql_ex = "mb_level = {$row['as_grade']} ";
				}
			}else{
				if($board_grant_c != 1){
					$sql_ex = "mb_level >= {$board_grant_c} ";
				}
			}

			if($setting == "notice") $sql_setting = $sql_setting . " and gpr_setting_notice = 'Y'";
			if($sql_ex == "none"){
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting $sql_limit";
			}else{
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting and gpr_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex) $sql_limit";
			}
			
		}

	}else{

		if(($func != "counsel" && $gnu_config['board_grant'] == 'N') || $board_grant_c == 1)
		{
			if($setting == "notice") $sql_setting = $sql_setting . " and gpr_setting_notice = 'Y'";
			$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting $sql_limit";
		}
		else
		{
			if($setting == "notice") $sql_setting = $sql_setting . " and gpr_setting_notice = 'Y'";
			$sql_ex = "mb_level >= {$board_grant_c} ";
			 
			$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting and gpr_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex) $sql_limit";
		}
	}
	$devices_select = sql_query($query);
	return $devices_select;
}

function get_devices_push($type, $setting, $board_grant_c, $setting_board_table, $func = "none", $include_mb_id = "")
{

	global $g5;
	$gnu_config = get_gnupushapp_config();

	$sql_setting = "1";

	if($gnu_config['build_sort'] == "A"){
		if($func != "secret" && $func != "counsel" && $gnu_config['board_grant'] == 'N')
		{
			if($setting == "notice") $sql_setting = $sql_setting . " and gpr_setting_notice = 'Y'";

			if($type == 'count')
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where $sql_setting ";
				$row = sql_fetch($query);
				return $row['cnt'];
			}
			else if($type == 'array')
			{
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting ";
				$devices_select = sql_query($query);
				return $devices_select;

			}
			else
			{
				return false;
			}
		}
		else
		{
			$sql = " select * from {$g5['board_table']} where bo_table = '{$setting_board_table}'";
			$row_board = sql_fetch($sql);

			$sql_ex = "none";

			if($setting == "notice") $sql_setting = $sql_setting . " and gpr_setting_notice = 'Y'";

			if($row_board['as_min'] != 0 && $row_board['as_max'] != 0){
				if($include_mb_id != ""){
					$sql_ex = "(as_level >= {$row_board['as_min']} and as_level <= {$row_board['as_max']}) or mb_id in ({$include_mb_id})";
				}else{
					$sql_ex = "as_level >= {$row_board['as_min']} and as_level <= {$row_board['as_max']} ";
				}
				
			}elseif($row_board['as_grade'] > 1 || $row_board['as_equal'] != 0){
				if($row_board['as_equal'] == 0){

					if($include_mb_id != ""){
						$sql_ex = "mb_level >= {$row_board['as_grade']} or mb_id in ({$include_mb_id})";
					}else{
						$sql_ex = "mb_level >= {$row_board['as_grade']} ";
					}
				}else{
					if($include_mb_id != ""){
						$sql_ex = "mb_level = {$row_board['as_grade']} or mb_id in ({$include_mb_id}) ";
					}else{
						$sql_ex = "mb_level = {$row_board['as_grade']} ";
					}
				}
			}else{
				if($board_grant_c != 1){
					if($include_mb_id != ""){
						$sql_ex = "mb_level >= {$board_grant_c} or mb_id in ({$include_mb_id})";
					}else{
						$sql_ex = "mb_level >= {$board_grant_c}";
					}
				}				
			}

			if($sql_ex == "none"){

				if($type == 'count')
				{
					if($include_mb_id != ""){
						$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where $sql_setting and gpr_mb_id in ({$include_mb_id}) ";
					}else{
						$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where $sql_setting ";
					}
					$row = sql_fetch($query);
					return $row['cnt'];
				}
				else if($type == 'array')
				{
					if($include_mb_id != ""){
						$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting and gpr_mb_id in ({$include_mb_id}) ";
					}else{
						$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting ";
					}
					$devices_select = sql_query($query);
					return $devices_select;
				}
				else
				{
					return false;
				}


			}else{

				if($type == 'count')
				{
					$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where $sql_setting and gpr_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex) ";
					$row = sql_fetch($query);
					return $row['cnt'];
				}
				else if($type == 'array')
				{
					$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting and gpr_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex) ";
					$devices_select = sql_query($query);
					return $devices_select;
				}
				else
				{
					return false;
				}

			}
		}

	}else{

		if(($func != "secret" && $func != "counsel" && $gnu_config['board_grant'] == 'N') || $board_grant_c == 1)
		{
			if($setting == "notice") $sql_setting = $sql_setting . " and gpr_setting_notice = 'Y'";

			if($type == 'count')
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where $sql_setting ";
				$row = sql_fetch($query);
				return $row['cnt'];
			}
			else if($type == 'array')
			{
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting ";
				$devices_select = sql_query($query);
				return $devices_select;

			}
			else
			{
				return false;
			}
		}
		else
		{
			if($setting == "notice") $sql_setting = $sql_setting . " and gpr_setting_notice = 'Y'";

			if($include_mb_id != ""){
				$sql_ex = "mb_level >= {$board_grant_c} or mb_id in ({$include_mb_id}) ";

			}else{
				$sql_ex = "mb_level >= {$board_grant_c} ";
			}

			if($type == 'count')
			{
				$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where $sql_setting and gpr_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex) ";
				$row = sql_fetch($query);
				return $row['cnt'];
			}
			else if($type == 'array')
			{
				$query = "select gpr_reg_id,gpr_mb_id,gpr_sort,gpr_other_setting,gpr_sync,gpr_os_version from g5_gnupushapp_gcmregid where $sql_setting and gpr_mb_id in (select mb_id from {$g5['member_table']} where $sql_ex) ";
				$devices_select = sql_query($query);
				return $devices_select;

			}
			else
			{
				return false;
			}
		}
	}
}


function setdefaultsettingboard($reg_id, $sort)
{
	global $g5;

	if(!$reg_id || $reg_id == "null" || $reg_id == "BLACKLISTED")
	{
		return;
	}

	$device_info = sql_fetch("SELECT * from g5_gnupushapp_gcmregid where gpr_reg_id = '$reg_id' ");
	$gpr_os_version = $device_info['gpr_os_version'];

	$gnu_config = get_gnupushapp_config();

	if($gnu_config['choose_board'] == "X") return;
	if($gnu_config['choose_board_s'] == "N") return;
	if($gnu_config['choose_board_s'] == "C" && !is_array($gnu_config['subscribe_default_module_srls'])) return;

	$setting_youngcart_all = 'N';
	if($gnu_config["youngcart_category_boolean"] == "Y") $setting_youngcart_all = 'Y';
	$iphonesound = "sound";
	$board_keyword = "";
	$youngcart_keyword = "";
	$other_setting_array = array($iphonesound,$board_keyword,$youngcart_keyword);
	$osa = serialize($other_setting_array);

	$setting_newpost = 'N';
	$setting_newcom = 'N';
	$setting_youngcart = 'N';
	if($gnu_config['choose_board_s'] == "D" || $gnu_config['choose_board_s'] == "Y" || $gnu_config['choose_board_s'] == "F" || $gnu_config['choose_board_s'] == "F2" || $gnu_config['choose_board_s'] == "C") $setting_newpost = 'Y';
	if($gnu_config['choose_board_s'] == "D" || $gnu_config['choose_board_s'] == "F" || ($gnu_config['choose_board_s'] == "C" && $gnu_config["subscribe_comments"] == "Y")) $setting_newcom = 'Y';
	if($gnu_config["use_youngcart"] == "Y") $setting_youngcart = 'Y';

	if($gnu_config['choose_board_s'] == "C")
	{
		foreach($gnu_config['subscribe_default_module_srls'] as $key)
		{

			sql_query(" INSERT INTO g5_gnupushapp_subscribe 
					set gss_reg_id = '{$reg_id}',
					gss_bo_table = '{$key}',
					gss_ca_name = 'none',
					gss_post_subscribe = 'Y',
					gss_is_youngcart = 'N',
					gss_post_comment_subscribe = '{$setting_newcom}',
					gss_post_subscribe_onoff = '{$setting_newpost}',
					gss_setting_youngcart_all = '{$setting_youngcart_all}',
					gss_sort = '{$sort}',
					gss_other_setting = '{$osa}',
					gpr_os_version = '{$gpr_os_version}',
					gss_sync = 'N',
					gss_regdate = '".G5_TIME_YMDHIS."'
					", true);

				
				
			if(is_array($gnu_config['category_module_srls']) && in_array($key, $gnu_config['category_module_srls']))
			{
				$sql = " select * from {$g5['board_table']} where bo_table = '{$key}'";
				$row = sql_fetch($sql);
				if($row['bo_use_category'] && $row['bo_category_list'])
				{
					$categories = explode("|",$row['bo_category_list']);
					foreach($categories as $val)
					{
						sql_query(" INSERT INTO g5_gnupushapp_subscribe 
							set gss_reg_id = '{$reg_id}',
							gss_bo_table = '{$key}',
							gss_ca_name = '{$val}',
							gss_post_subscribe = 'Y',
							gss_is_youngcart = 'N',
							gss_post_comment_subscribe = '{$setting_newcom}',
							gss_post_subscribe_onoff = '{$setting_newpost}',
							gss_setting_youngcart_all = '{$setting_youngcart_all}',
							gss_sort = '{$sort}',
							gss_other_setting = '{$osa}',
							gpr_os_version = '{$gpr_os_version}',
							gss_sync = 'N',
							gss_regdate = '".G5_TIME_YMDHIS."'
							", true);
					}
				}
			}
		}
	}
	else
	{

		foreach($gnu_config['module_order'] as $key)
		{
			$key_array = explode("#/", $key);
			if(count($key_array) > 2){
				$key_bo_table = "";
				for($i=1;$i<count($key_array);$i++){
					if($i==1){
						$key_bo_table = $key_array[$i];
					}else{
						$key_bo_table .= "#/".$key_array[$i];
					}
				}

			}else{
				$key_bo_table = $key_array[1];
			}
			$go_for_it = true;
			if(is_array($gnu_config['no_use_module_srls']) && in_array($key_bo_table, $gnu_config['no_use_module_srls'])) $go_for_it = false;
			if(is_array($gnu_config['only_admin_push_module_srls']) && in_array($key_bo_table, $gnu_config['only_admin_push_module_srls'])) $go_for_it = false;
			if(is_array($gnu_config['notice_module_srls']) && in_array($key_bo_table, $gnu_config['notice_module_srls'])) $go_for_it = false;

			if($go_for_it)
			{

				sql_query(" INSERT INTO g5_gnupushapp_subscribe 
					set gss_reg_id = '{$reg_id}',
					gss_bo_table = '{$key_bo_table}',
					gss_ca_name = 'none',
					gss_post_subscribe = 'Y',
					gss_is_youngcart = 'N',
					gss_post_comment_subscribe = '{$setting_newcom}',
					gss_post_subscribe_onoff = '{$setting_newpost}',
					gss_setting_youngcart_all = '{$setting_youngcart_all}',
					gss_sort = '{$sort}',
					gpr_os_version = '{$gpr_os_version}',
					gss_other_setting = '{$osa}',
					gss_sync = 'N',
					gss_regdate = '".G5_TIME_YMDHIS."'
					", true);

				
				
				if(is_array($gnu_config['category_module_srls']) && in_array($key_bo_table, $gnu_config['category_module_srls']))
				{
					$sql = " select * from {$g5['board_table']} where bo_table = '{$key_bo_table}'";
					$row = sql_fetch($sql);
					if($row['bo_use_category'] && $row['bo_category_list'])
					{
						$categories = explode("|",$row['bo_category_list']);
						foreach($categories as $val)
						{
							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$reg_id}',
								gss_bo_table = '{$key_bo_table}',
								gss_ca_name = '{$val}',
								gss_post_subscribe = 'Y',
								gss_is_youngcart = 'N',
								gss_post_comment_subscribe = '{$setting_newcom}',
								gss_post_subscribe_onoff = '{$setting_newpost}',
								gss_setting_youngcart_all = '{$setting_youngcart_all}',
								gss_sort = '{$sort}',
								gss_other_setting = '{$osa}',
								gpr_os_version = '{$gpr_os_version}',
								gss_sync = 'N',
								gss_regdate = '".G5_TIME_YMDHIS."'
								", true);
						}
					}
				}
			}
		}
	}

	if($gnu_config['youngcart_category'] == "Y" && defined('G5_USE_SHOP') && G5_USE_SHOP)
	{
		//1단계****************************
		$sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '2' and ca_use = '1' order by ca_order ";
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++)
		{
			$ca_id = $row['ca_id'];
			$ca_name = $row['ca_name'];

			sql_query(" INSERT INTO g5_gnupushapp_subscribe 
				set gss_reg_id = '{$reg_id}',
				gss_bo_table = 'none',
				gss_ca_name = '{$ca_id}',
				gss_is_youngcart = 'Y',
				gss_post_subscribe = 'Y',
				gss_post_comment_subscribe = '{$setting_newcom}',
				gss_post_subscribe_onoff = '{$setting_youngcart}',
				gss_setting_youngcart_all = '{$setting_youngcart_all}',
				gss_sort = '{$sort}',
				gss_other_setting = '{$osa}',
				gpr_os_version = '{$gpr_os_version}',
				gss_sync = 'N',
				gss_regdate = '".G5_TIME_YMDHIS."'
				", true);

			//2단계****************************
			$sql2 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '4' and ca_use = '1' and ca_id like '{$ca_id}%' order by ca_order ";
			$result2 = sql_query($sql2);
			for ($i=0; $row2=sql_fetch_array($result2); $i++)
			{
				$ca_id2 = $row2['ca_id'];
				$ca_name2 = $row2['ca_name'];

				sql_query(" INSERT INTO g5_gnupushapp_subscribe 
					set gss_reg_id = '{$reg_id}',
					gss_bo_table = 'none',
					gss_ca_name = '{$ca_id2}',
					gss_is_youngcart = 'Y',
					gss_post_subscribe = 'Y',
					gss_post_comment_subscribe = '{$setting_newcom}',
					gss_post_subscribe_onoff = '{$setting_youngcart}',
					gss_setting_youngcart_all = '{$setting_youngcart_all}',
					gss_sort = '{$sort}',
					gss_other_setting = '{$osa}',
					gpr_os_version = '{$gpr_os_version}',
					gss_sync = 'N',
					gss_regdate = '".G5_TIME_YMDHIS."'
					", true);

				//3단계****************************
				$sql3 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '6' and ca_use = '1' and ca_id like '{$ca_id2}%' order by ca_order ";
				$result3 = sql_query($sql3);
				for ($i=0; $row3=sql_fetch_array($result3); $i++)
				{
					$ca_id3 = $row3['ca_id'];
					$ca_name3 = $row3['ca_name'];

					sql_query(" INSERT INTO g5_gnupushapp_subscribe 
						set gss_reg_id = '{$reg_id}',
						gss_bo_table = 'none',
						gss_ca_name = '{$ca_id3}',
						gss_is_youngcart = 'Y',
						gss_post_subscribe = 'Y',
						gss_post_comment_subscribe = '{$setting_newcom}',
						gss_post_subscribe_onoff = '{$setting_youngcart}',
						gss_setting_youngcart_all = '{$setting_youngcart_all}',
						gss_sort = '{$sort}',
						gss_other_setting = '{$osa}',
						gpr_os_version = '{$gpr_os_version}',
						gss_sync = 'N',
						gss_regdate = '".G5_TIME_YMDHIS."'
						", true);

					//4단계****************************
					$sql4 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '8' and ca_use = '1' and ca_id like '{$ca_id3}%' order by ca_order ";
					$result4 = sql_query($sql4);
					for ($i=0; $row4=sql_fetch_array($result4); $i++)
					{
						$ca_id4 = $row4['ca_id'];
						$ca_name4 = $row4['ca_name'];

						sql_query(" INSERT INTO g5_gnupushapp_subscribe 
							set gss_reg_id = '{$reg_id}',
							gss_bo_table = 'none',
							gss_ca_name = '{$ca_id4}',
							gss_is_youngcart = 'Y',
							gss_post_subscribe = 'Y',
							gss_post_comment_subscribe = '{$setting_newcom}',
							gss_post_subscribe_onoff = '{$setting_youngcart}',
							gss_setting_youngcart_all = '{$setting_youngcart_all}',
							gss_sort = '{$sort}',
							gss_other_setting = '{$osa}',
							gpr_os_version = '{$gpr_os_version}',
							gss_sync = 'N',
							gss_regdate = '".G5_TIME_YMDHIS."'
							", true);

						//5단계****************************
						$sql5 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '10' and ca_use = '1' and ca_id like '{$ca_id4}%' order by ca_order ";
						$result5 = sql_query($sql5);
						for ($i=0; $row5=sql_fetch_array($result5); $i++)
						{
							$ca_id5 = $row5['ca_id'];
							$ca_name5 = $row5['ca_name'];

							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$reg_id}',
								gss_bo_table = 'none',
								gss_ca_name = '{$ca_id5}',
								gss_is_youngcart = 'Y',
								gss_post_subscribe = 'Y',
								gss_post_comment_subscribe = '{$setting_newcom}',
								gss_post_subscribe_onoff = '{$setting_youngcart}',
								gss_setting_youngcart_all = '{$setting_youngcart_all}',
								gss_sort = '{$sort}',
								gss_other_setting = '{$osa}',
								gpr_os_version = '{$gpr_os_version}',
								gss_sync = 'N',
								gss_regdate = '".G5_TIME_YMDHIS."'
								", true);

						}

					}

				}

			}


		}


	}
}

?>