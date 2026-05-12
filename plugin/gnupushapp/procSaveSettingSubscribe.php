<?php
include_once('./_common.php');

$bo_table = htmlspecialchars($_REQUEST['bo_table']);
$onoff = htmlspecialchars($_REQUEST['onoff']);

if($_SESSION['reg_id'])
{
	$reg_id = $_SESSION['reg_id'];

	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
		if($onoff == "off")
		{
			//off일 때는 권한 검사 필요없이 취소함...
			$sql = " delete from g5_gnupushapp_subscribe where gss_bo_table = '$bo_table' and gss_reg_id = '$reg_id' and gss_is_youngcart = 'N' ";
			sql_query($sql);
			echo "off_ok";
		}
		else
		{
			$setting_newpost = $row_1['gpr_setting_newpost'];
			$setting_newcom = $row_1['gpr_setting_newcom'];
			$setting_youngcart_all = $row_1['gpr_setting_youngcart_all'];
			$setting_sort = $row_1['gpr_sort'];
			$setting_other_setting = $row_1['gpr_other_setting'];
			$setting_sync = $row_1['gpr_sync'];
			if($setting_sync != "N") $setting_mb_id = $row_1['gpr_mb_id'];
		
			$gnu_config = get_gnupushapp_config();
			$sql = " select * from {$g5['board_table']} where bo_table = '$bo_table'";
			$row = sql_fetch($sql);

			//권한체크
			$level = 1;
			$level_point = 1;

			if($setting_sync != "N")
			{
				$member = get_member($setting_mb_id);
				$level = intval($member['mb_level']);
				if($gnu_config['build_sort'] == "A"){
					$level_point = intval($member['as_level']);
				}
			}

			$grnt = false;
			if($gnu_config['board_grant'] == "Y" || $gnu_config['board_grant'] == "D")
			{
				if($gnu_config['build_sort'] == "A"){
					if($row['as_min'] != 0 && $row['as_max'] != 0){
						if($level_point < $row['as_min']) $grnt = true;
						if($level_point > $row['as_max']) $grnt = true;
					}elseif($row['as_grade'] > 1 || $row['as_equal'] != 0){
						if($row['as_equal'] == 0){
							if($level < $row['as_grade']) $grnt = true;
						}else{
							if($level != $row['as_grade']) $grnt = true;
						}
					}else{
						if($level < $row[$gnu_config['board_grant_c']]) $grnt = true;
					}

				}else{
					if($level < $row[$gnu_config['board_grant_c']]) $grnt = true;
				}
			}

			//구독설정
			if($grnt){
				echo "limit_grant";
			}else{
				//삭제 후 재입력
				$sql = " delete from g5_gnupushapp_subscribe where gss_bo_table = '$bo_table' and gss_reg_id = '$reg_id' and gss_is_youngcart = 'N' ";
				sql_query($sql);

				if($setting_sync != "N"){
					sql_query(" INSERT INTO g5_gnupushapp_subscribe 
						set gss_reg_id = '$reg_id',
						gss_bo_table = '$bo_table',
						gss_ca_name = 'none',
						gss_is_youngcart = 'N',
						gss_post_subscribe = 'Y',
						gss_post_comment_subscribe = '$setting_newcom',
						gss_post_subscribe_onoff = '$setting_newpost',
						gss_setting_youngcart_all = '$setting_youngcart_all',
						gss_mb_id = '$setting_mb_id',
						gss_sort = '$setting_sort',
						gss_other_setting = '$setting_other_setting',
						gss_sync = '$setting_sync',
						gss_regdate = '".G5_TIME_YMDHIS."'
						", true);
				}else{
					sql_query(" INSERT INTO g5_gnupushapp_subscribe 
						set gss_reg_id = '$reg_id',
						gss_bo_table = '$bo_table',
						gss_ca_name = 'none',
						gss_is_youngcart = 'N',
						gss_post_subscribe = 'Y',
						gss_post_comment_subscribe = '$setting_newcom',
						gss_post_subscribe_onoff = '$setting_newpost',
						gss_setting_youngcart_all = '$setting_youngcart_all',
						gss_sort = '$setting_sort',
						gss_other_setting = '$setting_other_setting',
						gss_sync = '$setting_sync',
						gss_regdate = '".G5_TIME_YMDHIS."'
						", true);
				}
				echo "on_ok";
			}

		}
	}
	else
	{
		echo "regid_fail";
	}
}else{
	echo "regid_fail";
}

?>