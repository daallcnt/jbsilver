<?php
$sub_menu = "200150";
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$count = count($_POST['chk']);

if(!$count)
    alert('수정할 레벨명을 1개이상 선택해 주세요.');

for ($i=0; $i<$count; $i++)
{
    $k     = $_POST['chk'][$i];
    $gm_no = $_POST['gm_no'][$k];

    if($_POST['act_button'] == '선택수정') {
        $sql = " update g5_mblevel 
                    set gm_name    = '{$_POST['gm_name'][$k]}'
                  where gm_no         = '{$gm_no}' ";
        sql_query($sql);
    }
}

goto_url('./mem_form.php');
?>