<?php
include_once('./_common.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$setting_board = htmlspecialchars($_REQUEST['setting_board']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";

if($str_mp == $masterpassword && $reg_id)
{
	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
		$setting_youngcart = $row_1['gpr_setting_youngcart'];
		$setting_newpost = $row_1['gpr_setting_newpost'];
		$setting_newcom = $row_1['gpr_setting_newcom'];
		$setting_youngcart_all = $row_1['gpr_setting_youngcart_all'];
		$setting_sort = $row_1['gpr_sort'];
		$setting_other_setting = $row_1['gpr_other_setting'];
		$setting_sync = $row_1['gpr_sync'];
		if($setting_sync != "N") $setting_mb_id = $row_1['gpr_mb_id'];

		//구독게시판 & 영카트 설정
		$setting_board_Array = explode("adfrewrgfdv#sdsf%sdfs",$setting_board);
		$delete_subscribe_ix_array = array();

		if($setting_board_Array[0] == "none"){
			
			//영카트 설정

			$category_name_array = array();
			$subscribe_ix_array = array();

			$query = "select * from g5_gnupushapp_subscribe where gss_reg_id = '{$reg_id}' and gss_is_youngcart = 'Y' ";
			$devices_select = sql_query($query);
			for ($i=0; $rowddd=sql_fetch_array($devices_select); $i++)
			{
				$category_name_array[$i] = $rowddd['gss_ca_name'];
				$subscribe_ix_array[$i] = $rowddd['gss_ix'];
			}

			$i = 0;
			$setting_y = explode('/-', $setting_board_Array[1]);
			$setting_data_y = explode('a12345gnupushapp54321a', $setting_y[0]);
			if($setting_data_y[2] == "true")
			{
				$youngcart_all = "Y";
			}else{
				$youngcart_all = "N";
			}
			if($setting_youngcart_all != $youngcart_all)
			{
				$sql = " update g5_gnupushapp_gcmregid
					set gpr_setting_youngcart_all = '{$youngcart_all}'
					where gpr_reg_id = '{$reg_id}' ";
				sql_query($sql);
				sql_query(" update g5_gnupushapp_subscribe 
					set gss_setting_youngcart_all = '{$youngcart_all}'
					where gss_reg_id = '{$reg_id}' ", true);
			}

			foreach($setting_y as $val3)
			{
				$setting_data = explode('a12345gnupushapp54321a', $val3);
					
				if(is_array($category_name_array) && in_array($setting_data[1], $category_name_array))
				{
					$key = array_search($setting_data[1], $category_name_array);

					if($setting_data[2] == "false")
					{
						array_push($delete_subscribe_ix_array, $subscribe_ix_array[$key]);
					}
				}
				else
				{
					if($setting_data[2] == "true")
					{
						if($setting_sync != "N"){
							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$reg_id}',
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
								set gss_reg_id = '{$reg_id}',
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
						}
					}
				}
				$i++;
			}

		}elseif($setting_board_Array[1] == "none"){
			//구독게시판 설정

			$botable_category_array = array();
			$subscribe_ix_array = array();
			$new_botable_category_array = array();

			$query = "select * from g5_gnupushapp_subscribe where gss_reg_id = '{$reg_id}' and gss_is_youngcart = 'N' ";
			$devices_select = sql_query($query);
			for ($i=0; $rowddd=sql_fetch_array($devices_select); $i++)
			{
				$botable_category_array[$i] = $rowddd['gss_bo_table'] . "%#" . $rowddd['gss_ca_name'];
				$subscribe_ix_array[$i] = $rowddd['gss_ix'];
			}

			$setting_b = explode('/-', $setting_board_Array[0]);

			foreach($setting_b as $val3)
			{
				$setting_data = explode('%#', $val3);

				$category_name = "none";

				if($setting_data[2] != "none")
				{
					$category_name = $setting_data[2];
				}

				$search_key = $setting_data[0] . "%#" . $category_name;
				array_push($new_botable_category_array, $search_key);

				if(is_array($botable_category_array) && in_array($search_key, $botable_category_array))
				{
					$key = array_search($search_key, $botable_category_array);

					if($setting_data[1] == "false")
					{
						array_push($delete_subscribe_ix_array, $subscribe_ix_array[$key]);
					}
				}
				else
				{

					if($setting_data[1] == "true")
					{
						if($setting_sync != "N"){							
							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$reg_id}',
								gss_bo_table = '{$setting_data[0]}',
								gss_ca_name = '{$category_name}',
								gss_post_subscribe = 'Y',
								gss_is_youngcart = 'N',
								gss_post_comment_subscribe = '{$setting_newcom}',
								gss_post_subscribe_onoff = '{$setting_newpost}',
								gss_setting_youngcart_all = '{$setting_youngcart_all}',
								gss_mb_id = '{$setting_mb_id}',
								gss_sort = '{$setting_sort}',
								gss_other_setting = '{$setting_other_setting}',
								gss_sync = '{$setting_sync}',
								gss_regdate = '".G5_TIME_YMDHIS."'
								", true);
						}else{
							sql_query(" INSERT INTO g5_gnupushapp_subscribe 
								set gss_reg_id = '{$reg_id}',
								gss_bo_table = '{$setting_data[0]}',
								gss_ca_name = '{$category_name}',
								gss_post_subscribe = 'Y',
								gss_is_youngcart = 'N',
								gss_post_comment_subscribe = '{$setting_newcom}',
								gss_post_subscribe_onoff = '{$setting_newpost}',
								gss_setting_youngcart_all = '{$setting_youngcart_all}',
								gss_sort = '{$setting_sort}',
								gss_other_setting = '{$setting_other_setting}',
								gss_sync = '{$setting_sync}',
								gss_regdate = '".G5_TIME_YMDHIS."'
								", true);
						}
					}
				}
			}
			$iiis = 0;
			foreach($botable_category_array as $val3)
			{
				if(is_array($new_botable_category_array) && !in_array($val3, $new_botable_category_array))
				{
					array_push($delete_subscribe_ix_array, $subscribe_ix_array[$iiis]);
				}
				$iiis++;
			}
		}

		if(count($delete_subscribe_ix_array) > 0)
		{
			$subscribe_ix = "";
			for ($i=0; $i<count($delete_subscribe_ix_array); $i++)
			{
				if($subscribe_ix == ""){
					$subscribe_ix = "'".$delete_subscribe_ix_array[$i]."'";
				}else{
					$subscribe_ix = $subscribe_ix . ",'".$delete_subscribe_ix_array[$i]."'";
				}
			}
			
			$sql = " delete from g5_gnupushapp_subscribe where gss_ix in ({$subscribe_ix}) ";
			sql_query($sql);

		}

		$response = "ok";
	}

}

$array = array("response" => $response);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>