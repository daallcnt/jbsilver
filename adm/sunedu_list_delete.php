<?
$sub_menu = "750100";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "d");

check_admin_token();

$bo_table = "education";
for ($i=0; $i<count($chk); $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];
	$s_id = $_POST['s_id'][$k];


        $row = sql_fetch(" select count(*) as cnt from wp_edu where s_id = '$s_id' ");
        if ($row['cnt'])
            alert("이 교육에 속한 신청자가 존재하여 교육을 삭제할 수 없습니다.\\n\\n이 교육에 속한 신청자를 먼저 삭제하여 주십시오.", './edumember_list.php?s_id='.$s_id);


		$sql = " select s_id from wp_education where s_id = '$s_id' ";
		$row = sql_fetch($sql);
        // 업로드된 파일이 있다면 파일삭제
        $sql2 = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['s_id']}' ";
        $result2 = sql_query($sql2);
        while ($row2 = sql_fetch_array($result2)) {
            @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row2['bf_file']);
            // 썸네일삭제
            if(preg_match("/\.({$config['cf_image_extension']})$/i", $row2['bf_file'])) {
                delete_board_thumbnail($bo_table, $row2['bf_file']);
            }
        }

        // 에디터 썸네일 삭제
        delete_editor_thumbnail($row['contents']);

        // 파일테이블 행 삭제
        sql_query(" delete from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['s_id']}' ");

        sql_query(" delete from wp_education where s_id = '$s_id' ");
}

goto_url("./sunedu_list.php?$qstr");
?>
