<?php
include_once('../common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

/***/

$query = "show columns from g5_gnupushapp_gcmregid like 'gpr_setting_newpost' ";
$res = sql_fetch($query);
if (empty($res)) {	
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_newpost` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_newcom` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_myreply` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_mypost_com` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_mycom_com` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_mycom_tail` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_notice` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_message` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_mention` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_recommendation` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_marketing` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_youngcart` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_chat` char(1) NOT NULL DEFAULT 'N';", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD `gpr_setting_youngcart_all` char(1) NOT NULL DEFAULT 'N';", true);

	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_newpost` (`gpr_setting_newpost`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_newcom` (`gpr_setting_newcom`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_myreply` (`gpr_setting_myreply`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_mypost_com` (`gpr_setting_mypost_com`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_mycom_com` (`gpr_setting_mycom_com`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_mycom_tail` (`gpr_setting_mycom_tail`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_notice` (`gpr_setting_notice`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_message` (`gpr_setting_message`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_mention` (`gpr_setting_mention`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_recommendation` (`gpr_setting_recommendation`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_marketing` (`gpr_setting_marketing`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_youngcart` (`gpr_setting_youngcart`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_chat` (`gpr_setting_chat`);", true);
	sql_query(" ALTER TABLE g5_gnupushapp_gcmregid ADD INDEX `idx_gpr_setting_youngcart_all` (`gpr_setting_youngcart_all`);", true);
}

$query = "show columns from g5_gnupushapp_gcmregid like 'gpr_setting_board' ";
$res = sql_fetch($query);
if (empty($res)) {
	goto_url('./gpa_config.php', false);	
}else{
	$done = false;
	$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where gpr_setting_board <> 'none' or gpr_setting <> 'none' ";
	$row = sql_fetch($query);
	if($row['cnt'] == 0)
	{
		$done = true;
	}else{
		$query = "select * from g5_gnupushapp_gcmregid where gpr_setting_board <> 'none' or gpr_setting <> 'none' ";
		$devices_select = sql_query($query);
		for ($i=0; $rowddd=sql_fetch_array($devices_select); $i++)
		{
			$setting_newpost = $rowddd['gpr_setting_newpost'];
			$setting_newcom = $rowddd['gpr_setting_newcom'];
			$setting_reply = $rowddd['gpr_setting_myreply'];
			$setting_mypost_com = $rowddd['gpr_setting_mypost_com'];
			$setting_mycom_com = $rowddd['gpr_setting_mycom_com'];
			$setting_mycom_tail = $rowddd['gpr_setting_mycom_tail'];
			$setting_notice = $rowddd['gpr_setting_notice'];
			$setting_message = $rowddd['gpr_setting_message'];
			$setting_mention = $rowddd['gpr_setting_mention'];
			$setting_recommendation = $rowddd['gpr_setting_recommendation'];
			$setting_marketing = $rowddd['gpr_setting_marketing'];
			$setting_youngcart = $rowddd['gpr_setting_youngcart'];
			$setting_chat = $rowddd['gpr_setting_chat'];

			$setting_sort = $rowddd['gpr_sort'];
			$setting_other_setting = $rowddd['gpr_other_setting'];
			$setting_sync = $rowddd['gpr_sync'];
			if($setting_sync != "N") $setting_mb_id = $rowddd['gpr_mb_id'];

			if($rowddd['gpr_setting'] != 'none')
			{
				$setting_push = str_replace(array('newpost_', 'newcom_', 'myreply_', 'mypost_com_', 'mycom_com_', 'mycom_tail_', 'notice_', 'message_', 'mention_', 'recommendation_', 'marketing_'), '', $rowddd['gpr_setting']);
				$setting = explode("-", $setting_push);

				if($rowddd['gpr_youngcart_setting'] == "true") $setting_youngcart = "Y";
				$other_setting_array = unserialize($rowddd['gpr_other_setting']);
				if(count($other_setting_array) >= 4){
					if($other_setting_array[3] == "false")
					{
						$setting_chat = "N";
					}
				}

				$i = 0;

				foreach($setting as $val2)
				{

					if($i == 0 && $val2 == "true")
					{
						$setting_newpost = "Y";
					}
					if($i == 1 && $val2 == "true")
					{
						$setting_newcom = "Y";
					}
					if($i == 2 && $val2 == "true")
					{
						$setting_reply = "Y";
					}
					if($i == 3 && $val2 == "true")
					{
						$setting_mypost_com = "Y";
					}
					if($i == 4 && $val2 == "true")
					{
						$setting_mycom_com = "Y";
					}
					if($i == 5 && $val2 == "true")
					{
						$setting_mycom_tail = "Y";
					}
					if($i == 6 && $val2 == "true")
					{
						$setting_notice = "Y";
					}
					if($i == 7 && $val2 == "true")
					{
						$setting_message = "Y";
					}
					if($i == 8 && $val2 == "true")
					{
						$setting_mention = "Y";
					}
					if($i == 9 && $val2 == "true")
					{
						$setting_recommendation = "Y";
					}
					if($i == 10 && $val2 == "true")
					{
						$setting_marketing = "Y";
					}

					$i++;
				}

				$sql = " update g5_gnupushapp_gcmregid
						set gpr_setting = 'none',
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
						gpr_setting_chat = '{$setting_chat}'
						where gpr_reg_id = '{$rowddd['gpr_reg_id']}' ";
				sql_query($sql);
			}

			if($rowddd['gpr_setting_board'] != 'none')
			{
				$setting_board_Array = explode("adfrewrgfdv#sdsf%sdfs",$rowddd['gpr_setting_board']);

				$setting_b = explode('/-', $setting_board_Array[0]);
				$setting_y = explode('/-', $setting_board_Array[1]);

				$setting_data_y = explode('a12345gnupushapp54321a', $setting_y[0]);
				if($setting_data_y[2] == "true")
				{
					$youngcart_all = "Y";
				}else{
					$youngcart_all = "N";
				}

				$sql = " update g5_gnupushapp_gcmregid
					set gpr_setting_youngcart_all = '{$youngcart_all}'
					where gpr_reg_id = '{$rowddd['gpr_reg_id']}' ";
				sql_query($sql);

				foreach($setting_b as $val3)
				{
					$setting_data = explode('a12345gnupushapp54321a', $val3);

					$category_name = "none";

					if($setting_data[2] != "none")
					{
						$category_name = $setting_data[2];
					}

					if($setting_data[1] == "true")
					{
						if($setting_sync != "N"){
							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$rowddd['gpr_reg_id']}',
								gss_bo_table = '{$setting_data[0]}',
								gss_ca_name = '{$category_name}',
								gss_post_subscribe = 'Y',
								gss_is_youngcart = 'N',
								gss_post_comment_subscribe = '{$setting_newcom}',
								gss_post_subscribe_onoff = '{$setting_newpost}',
								gss_setting_youngcart_all = '{$youngcart_all}',
								gss_mb_id = '{$setting_mb_id}',
								gss_sort = '{$setting_sort}',
								gss_other_setting = '{$setting_other_setting}',
								gss_sync = '{$setting_sync}',
								gss_regdate = '".G5_TIME_YMDHIS."'
								", true);
						}else{
							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$rowddd['gpr_reg_id']}',
								gss_bo_table = '{$setting_data[0]}',
								gss_ca_name = '{$category_name}',
								gss_post_subscribe = 'Y',
								gss_is_youngcart = 'N',
								gss_post_comment_subscribe = '{$setting_newcom}',
								gss_post_subscribe_onoff = '{$setting_newpost}',
								gss_setting_youngcart_all = '{$youngcart_all}',
								gss_sort = '{$setting_sort}',
								gss_other_setting = '{$setting_other_setting}',
								gss_sync = '{$setting_sync}',
								gss_regdate = '".G5_TIME_YMDHIS."'
								", true);

						}
					}
				}

				
				$i = 0;

				foreach($setting_y as $val3)
				{
					$setting_data = explode('a12345gnupushapp54321a', $val3);

					if($setting_data[2] == "true")
					{
						if($setting_sync != "N"){
							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$rowddd['gpr_reg_id']}',
								gss_bo_table = 'none',
								gss_ca_name = '{$setting_data[1]}',
								gss_is_youngcart = 'Y',
								gss_post_subscribe = 'Y',
								gss_post_comment_subscribe = '{$setting_newcom}',
								gss_post_subscribe_onoff = '{$setting_youngcart}',
								gss_setting_youngcart_all = '{$youngcart_all}',
								gss_mb_id = '{$setting_mb_id}',
								gss_sort = '{$setting_sort}',
								gss_other_setting = '{$setting_other_setting}',
								gss_sync = '{$setting_sync}',
								gss_regdate = '".G5_TIME_YMDHIS."'
								", true);
						}else{
							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$rowddd['gpr_reg_id']}',
								gss_bo_table = 'none',
								gss_ca_name = '{$setting_data[1]}',
								gss_is_youngcart = 'Y',
								gss_post_subscribe = 'Y',
								gss_post_comment_subscribe = '{$setting_newcom}',
								gss_post_subscribe_onoff = '{$setting_youngcart}',
								gss_setting_youngcart_all = '{$youngcart_all}',
								gss_sort = '{$setting_sort}',
								gss_other_setting = '{$setting_other_setting}',
								gss_sync = '{$setting_sync}',
								gss_regdate = '".G5_TIME_YMDHIS."'
								", true);
						}
					}
					$i++;
				}

				$sql = " update g5_gnupushapp_gcmregid
						set gpr_setting_board = 'none'
						where gpr_reg_id = '{$rowddd['gpr_reg_id']}' ";
				sql_query($sql);

			}
		}
	}

	if(!$done)
	{
		$query = "select count(*) as 'cnt' from g5_gnupushapp_gcmregid where gpr_setting_board <> 'none' or gpr_setting <> 'none' ";
		$row = sql_fetch($query);
		if($row['cnt'] == 0)
		{
			$done = true;
		}
	}

	if($done)
	{
		sql_query(" ALTER TABLE g5_gnupushapp_gcmregid DROP `gpr_setting_board` ;", true);
		sql_query(" ALTER TABLE g5_gnupushapp_gcmregid DROP `gpr_setting` ;", true);
		sql_query(" ALTER TABLE g5_gnupushapp_gcmregid DROP `gpr_youngcart_setting` ;", true);
	}

	goto_url('setting_basic.php', false);
}

?>
