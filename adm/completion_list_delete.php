<?php
$sub_menu = "750300";
include_once("./_common.php");

check_demo();



    if ($is_admin != 'super')
        alert('삭제는 최고관리자만 가능합니다.');

    auth_check($auth[$sub_menu], 'd');

    check_admin_token();


if ($_POST['act_button'] == "전체삭제") {
	if($sca) { $where = " where SUBSTRING(schedule,1,4) = '$sca' "; }else{ $where = ""; }
    
	sql_query(" delete from wp_completion $where ");


} else if ($_POST['act_button'] == "선택삭제") {
	if (!count($_POST['chk'])) {
		alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
	}

	    for ($i=0; $i<count($_POST['chk']); $i++) {
	        // 실제 번호를 넘김
	        $k = $_POST['chk'][$i];
			$c_id = (int) $_POST['c_id'][$k];
	        sql_query(" delete from wp_completion where c_id = '$c_id' ");
	    }


}

goto_url("./completion_list.php?$qstr");
?>
