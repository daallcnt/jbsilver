<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

if($reg_id = get_session('reg_id')){

	$gnu_config = get_gnupushapp_config();

	$device_info = get_device_info_by_regid($reg_id);

	global $g5;

	$quick_bo_table = htmlspecialchars($_POST['quick_bo_table']);
	$quick_wr_id = htmlspecialchars($_POST['quick_wr_id']);
	$board_config = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$quick_bo_table' ");

	$src = "none";
	$content = "none";

	$read_ok = false;

	if($board_config['bo_read_level'] == 1){
		$read_ok = true;
	}elseif($device_info['gpr_sync'] != "N"){
		$sql_memb = "select mb_level from {$g5['member_table']} where mb_id = '{$device_info['gpr_mb_id']}' ";
		$result_mb = sql_fetch($sql_memb);
		if($result_mb['mb_level'] >= $board_config['bo_read_level']) $read_ok = true;
	}

	if($read_ok){

		$tmp_write_table = $g5['write_prefix'] . $quick_bo_table; // 게시판 테이블 전체이름
		$sql = " select * from {$tmp_write_table} where wr_id = '{$quick_wr_id}' ";
		$result_row = sql_fetch($sql);

		if(strpos($result_row['wr_option'], "secret") !== false){

			$content = "secret";

		}else{

			$content = cut_str(strip_tags($result_row['wr_content']), 100, '');
			$content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;', '&#034;'), array('<', '>', '"', ' ', '&','"'), stripslashes($content));

			$thumb = get_list_thumbnail($quick_bo_table, $quick_wr_id, 300, 300);

			if($thumb['src']) {
				$src = $thumb['src'];
			}
			else
			{				
				if($gnu_config['build_sort'] == 'A')
				{
					$thumb = apms_wr_thumbnail($quick_bo_table, $result_row, 300, 300, false, true);
					if($thumb['src']) {
						$src = $thumb['src'];
					}
				}
			}
		}
	}

	$array = array("src" => $src, "message" => $content);

	$json = "";

	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;

}


exit();

?>