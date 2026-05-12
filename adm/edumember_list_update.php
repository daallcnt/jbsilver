<?php
$sub_menu = "750100";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        $sql = " update wp_edu
                    set progress       = '{$_POST['progress'][$k]}',
						payment  = '{$_POST['payment'][$k]}',
						edu  = '{$_POST['edu'][$k]}',
						mdate = '".G5_TIME_YMDHIS."' 
                  where e_id            = '{$_POST['e_id'][$k]}' ";
        sql_query($sql);
    }

} else if ($_POST['act_button'] == "선택삭제") {

    if ($is_admin != 'super')
        alert('삭제는 최고관리자만 가능합니다.');

    auth_check($auth[$sub_menu], 'd');

    check_token();

    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
		$e_id = $_POST['e_id'][$k];
        sql_query(" delete from wp_edu where e_id = '$e_id' ");
    }


}
if (isset($s_id)) $qstr .= "&s_id=$s_id";

goto_url('./edumember_list.php?'.$qstr);
?>
