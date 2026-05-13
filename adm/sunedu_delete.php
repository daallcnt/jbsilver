<?php
$sub_menu = "750100";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");

check_admin_token();

$bo_table = "education";
$s_id = isset($_GET['s_id']) ? (int) $_GET['s_id'] : 0;

if (!$s_id) {
    alert("삭제할 교육 정보가 올바르지 않습니다.");
}

$row = sql_fetch(" select count(s_id) as cnt from wp_edu where s_id = '$s_id' ");
if ($row['cnt'] > 0) {
    alert("이 교육과 관련된 신청자가 존재하므로 지원신청자를 삭제한 후 교육을 삭제하여 주십시오.");
}

$sql = " select s_id, contents from wp_education where s_id = '$s_id' ";
$row = sql_fetch($sql);
if (!$row['s_id']) {
    alert("삭제할 교육 정보를 찾을 수 없습니다.");
}

// 업로드된 파일이 있다면 파일삭제
$sql2 = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['s_id']}' ";
$result2 = sql_query($sql2);
while ($row2 = sql_fetch_array($result2)) {
    @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row2['bf_file']);
    // 썸네일삭제
    if (preg_match("/\.({$config['cf_image_extension']})$/i", $row2['bf_file'])) {
        delete_board_thumbnail($bo_table, $row2['bf_file']);
    }
}

// 에디터 썸네일 삭제
delete_editor_thumbnail($row['contents']);

// 파일테이블 행 삭제
sql_query(" delete from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['s_id']}' ");

$sql = " delete from wp_education where s_id = '$s_id' ";
sql_query($sql);

goto_url("./sunedu_list.php?$qstr");
?>
