<?php
include_once('../common.php');
include_once(G5_LIB_PATH.'/register.lib.php');



if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');
	
$mb_birth       = isset($_POST['mb_birth'])         ? trim($_POST['mb_birth'])       : "";
$mb_tel         = isset($_POST['mb_tel'])           ? trim($_POST['mb_tel'])         : "";
$mb_hp          = isset($_POST['mb_hp'])            ? trim($_POST['mb_hp'])          : "";
$mb_zip1        = isset($_POST['mb_zip'])           ? substr(trim($_POST['mb_zip']), 0, 3) : "";
$mb_zip2        = isset($_POST['mb_zip'])           ? substr(trim($_POST['mb_zip']), 3)    : "";
$mb_addr1       = isset($_POST['mb_addr1'])         ? trim($_POST['mb_addr1'])       : "";
$mb_addr2       = isset($_POST['mb_addr2'])         ? trim($_POST['mb_addr2'])       : "";
$mb_addr3       = isset($_POST['mb_addr3'])         ? trim($_POST['mb_addr3'])       : "";
$mb_addr_jibeon = isset($_POST['mb_addr_jibeon'])   ? trim($_POST['mb_addr_jibeon']) : "";
$mb_etc     = isset($_POST['mb_etc'])       ? trim($_POST['mb_etc'])     : "";
$mb_11 = ($mb11)?implode(",", $mb11):"";
$mb_12           = isset($_POST['mb_12'])             ? trim($_POST['mb_12'])           : "";
$mb_13           = isset($_POST['mb_13'])             ? trim($_POST['mb_13'])           : "";
$mb_14 = ($mb14)?implode(",", $mb14):"";
$mb_15           = isset($_POST['mb_15'])             ? trim($_POST['mb_15'])           : "";
$mb_16           = isset($_POST['mb_16'])             ? trim($_POST['mb_16'])           : "";
$mb_17 = ($mb17)?implode(",", $mb17):"";
$mb_18           = isset($_POST['mb_18'])             ? trim($_POST['mb_18'])           : "";
$mb_19 = ($mb19)?implode(",", $mb19):"";
$mb_20          = isset($_POST['mb_20'])            ? trim($_POST['mb_20'])          : "";	
$mb_21           = isset($_POST['mb_21'])             ? trim($_POST['mb_21'])           : "";	
	
	
$mb_tel         = clean_xss_tags($mb_tel);
$mb_zip1        = preg_replace('/[^0-9]/', '', $mb_zip1);
$mb_zip2        = preg_replace('/[^0-9]/', '', $mb_zip2);
$mb_addr1       = clean_xss_tags($mb_addr1);
$mb_addr2       = clean_xss_tags($mb_addr2);
$mb_addr3       = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';	
	
	
if ($msg = valid_mb_hp($mb_hp))     alert($msg, "", true, true);	
	

    if (!trim($_SESSION['ss_mb_id']))
        alert('로그인 되어 있지 않습니다.');


    $sql = " update {$g5['member_table']}
                set mb_tel = '{$mb_tel}',
					mb_hp = '{$mb_hp}',
					mb_birth = '{$mb_birth}',
                    mb_zip1 = '{$mb_zip1}',
                    mb_zip2 = '{$mb_zip2}',
                    mb_addr1 = '{$mb_addr1}',
                    mb_addr2 = '{$mb_addr2}',
                    mb_addr3 = '{$mb_addr3}',
                    mb_addr_jibeon = '{$mb_addr_jibeon}',
                    mb_etc = '{$mb_etc}',
                    mb_11 = '{$mb_11}',
                    mb_12 = '{$mb_12}',
                    mb_13 = '{$mb_13}',
                    mb_14 = '{$mb_14}',
                    mb_15 = '{$mb_15}',
                    mb_16 = '{$mb_16}',
                    mb_17 = '{$mb_17}',
                    mb_18 = '{$mb_18}',
                    mb_19 = '{$mb_19}',
                    mb_20 = '{$mb_20}',
                    mb_21 = '{$mb_21}'
              where mb_id = '$mb_id' ";
    sql_query($sql);	

if ($msg)
    echo '<script>alert(\''.$msg.'\');</script>';



if($iso == "ok") {
	$a_url = G5_ADMIN_URL."/card.php";	

}else{
	$a_url = "./index.php";	
}
        echo '
        <!doctype html>
        <html lang="ko">
        <head>
        <meta charset="utf-8">
        <title>취업상담카드</title>
        <body>
        <form name="fregisterupdate" method="post" action="'.$a_url.'">
        <input type="hidden" name="w" value="u">
		<input type="hidden" name="iso" value="'.$iso.'">
		<input type="hidden" name="case" value="view">
        <input type="hidden" name="mb_id" value="'.$mb_id.'">
        </form>
        <script>
        alert("취업상담카드가 등록 되었습니다.");
        document.fregisterupdate.submit();
        </script>
        </body>
        </html>';

/*
    $row  = sql_fetch(" select mb_password from {$g5['member_table']} where mb_id = '{$member['mb_id']}' ");
    $tmp_password = $row['mb_password'];
        echo '
        <!doctype html>
        <html lang="ko">
        <head>
        <meta charset="utf-8">
        <title>취업상담카드</title>
        <body>
        <form name="fregisterupdate" method="post" action="'.G5_HTTP_BBS_URL.'/register_form.php">
        <input type="hidden" name="w" value="u">
        <input type="hidden" name="mb_id" value="'.$mb_id.'">
        <input type="hidden" name="mb_password" value="'.$tmp_password.'">
        <input type="hidden" name="is_update" value="1">
        </form>
        <script>
        alert("취업상담카드가 등록 되었습니다.");
        document.fregisterupdate.submit();
        </script>
        </body>
        </html>';	
*/
?>