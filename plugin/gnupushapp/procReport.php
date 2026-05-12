<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$bo_table = htmlspecialchars($_REQUEST['bo_table']);
$wr_id = htmlspecialchars($_REQUEST['wr_id']);
$target_mb_id = htmlspecialchars($_REQUEST['target_mb_id']);
$bre_type = htmlspecialchars($_REQUEST['type']);

$board_config = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table' ");

$response = "fail";

if($board_config['bo_table'] && $is_member){

	$tmp_write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
	$sql = " select * from {$tmp_write_table} where wr_id = '$wr_id' ";
	$result_row = sql_fetch($sql);

	if($result_row['wr_id']){

		sql_query(" INSERT INTO g5_board_report_gnu 
					set bre_bo_table = '{$bo_table}',
					bre_wr_id = '{$wr_id}',
					bre_target_mb_id = '{$target_mb_id}',
					bre_mb_id = '{$_SESSION['ss_mb_id']}',
					bre_status = 'N',
					bre_type = '$bre_type',
					bre_regdate = '".G5_TIME_YMDHIS."'
					", true);
		$response = "ok";

	}
}
echo $response;
exit();

?>