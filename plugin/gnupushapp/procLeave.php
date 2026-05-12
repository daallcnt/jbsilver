<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";

if($str_mp == $masterpassword && $reg_id)
{
	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
        if ($member['mb_id'] && $is_admin != 'super'){
            // 회원탈퇴일을 저장
            $date = date("Ymd");
            $sql = " update {$g5['member_table']} set mb_leave_date = '{$date}' where mb_id = '{$member['mb_id']}' ";
            sql_query($sql);

            // 3.09 수정 (로그아웃)
            unset($_SESSION['ss_mb_id']);

            //소셜로그인 해제
            if(function_exists('social_member_link_delete')){
                social_member_link_delete($member['mb_id']);
            }

            $response = "ok";
        }
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

