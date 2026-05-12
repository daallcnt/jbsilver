<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$cc_ix = htmlspecialchars($_REQUEST['cc_ix']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($gnu_config['login_session'] == 'Y') $_SESSION['reg_id'] = htmlspecialchars($_REQUEST['reg_id']);

$response = "fail";
if($str_mp == $masterpassword && $cc_ix)
{
    $device_in = get_device_info_by_regid($_SESSION['reg_id']);
    
    //동기화회원일 경우 회원아이디로 검색, 비회원은 reg_id로 검색.
    if($device_in['gpr_sync'] == 'Y'){
        $mb_id_deleted = $device_in['gpr_mb_id'];
        $row_result_a = sql_fetch(" select * from g5_gnupushapp_newchatting_content where cc_ix = '$cc_ix' and mb_id = '{$mb_id_deleted}' ");
    }else{
        $row_result_a = sql_fetch(" select * from g5_gnupushapp_newchatting_content where cc_ix = '$cc_ix' and reg_id = '{$_SESSION['reg_id']}' ");
    }

    if($row_result_a['reg_id']){
        $response = "ok";
        sql_query("update g5_gnupushapp_newchatting_content set content = '삭제된 메시지입니다.', is_file = 'N', is_image = 'N' where cc_ix = '$cc_ix' ");
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