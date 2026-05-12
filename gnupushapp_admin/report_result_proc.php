<?php
include_once('../common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$bre_ix = htmlspecialchars($_REQUEST['bre_ix']);
$bre_status = htmlspecialchars($_REQUEST['bre_status']);

if($bre_status == "D"){
	$sql = " SELECT * FROM  g5_board_report_gnu where bre_ix = '$bre_ix'";
	$result = sql_fetch($sql);

	$tmp_write_table = $g5['write_prefix'] . $result['bre_bo_table']; // 게시판 테이블 전체이름
	$sql = " select * from $tmp_write_table where wr_id = '{$result['bre_wr_id']}' ";
	$result_row = sql_fetch($sql);

	$save_original_text = "";

	if($result['bre_type'] == "P") $save_original_text = $result_row['wr_subject'] . " / ";

	$save_original_text = $save_original_text . $result_row['wr_content'];

	$sql = " update g5_board_report_gnu
				set bre_status = '$bre_status',
				bre_original_text = '$save_original_text',
				bre_confirm = '".G5_TIME_YMDHIS."'
				where bre_ix = '$bre_ix' ";
	sql_query($sql);

	$new_tc = "해당 게시물이 정책위반으로 삭제처리되었습니다.";

	if($result['bre_type'] == "P"){
		$sql = " update $tmp_write_table
				set wr_subject = '$new_tc',
				wr_content = '$new_tc'
				where wr_id = '{$result['bre_wr_id']}' ";
		sql_query($sql);

	}

	if($result['bre_type'] == "C"){
		$sql = " update $tmp_write_table
				set wr_content = '$new_tc'
				where wr_id = '{$result['bre_wr_id']}' ";
		sql_query($sql);
	}	

}else{

	$sql = " update g5_board_report_gnu
				set bre_status = '$bre_status',
				bre_confirm = '".G5_TIME_YMDHIS."'
				where bre_ix = '$bre_ix' ";
	sql_query($sql);
}

echo "ok";


?>
