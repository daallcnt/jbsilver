<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$value = htmlspecialchars($_REQUEST['value']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($gnu_config['login_session'] == 'Y'){
	$_SESSION['reg_id'] = htmlspecialchars($_REQUEST['reg_id']);
	$_SESSION['ss_mb_id'] = null;
	$is_member = false;
	$device_in_sub = get_device_info_by_regid($_SESSION['reg_id']);
	if($device_in_sub['gpr_sync'] == "Y" || $device_in_sub['gpr_sync'] == "S" || $device_in_sub['gpr_sync'] == "D"){
		$_SESSION['ss_mb_id'] = $device_in_sub['gpr_mb_id'];
		$is_member = true;
	}
}

$response = "fail";

if($str_mp == $masterpassword)
{
    $free_memberinfo = sql_fetch("SELECT * from g5_gnupushapp_free_member where reg_id = '{$_SESSION['reg_id']}'");

    if(!$free_memberinfo['gpf_ix']){
        $response = "fail";
        $array = array("response" => $response);
        $json = "";
        $json = json_encode($array);
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Length: ' . mb_strlen($json));
        echo $json;
        exit();
    }

    $content = htmlspecialchars(urldecode(base64_decode($value)));

    sql_query(" INSERT INTO g5_gnupushapp_free_chatting_content 
                set sync = '{$free_memberinfo['sync']}',
                mb_id = '{$free_memberinfo['gpf_mb_id']}',
                reg_id = '{$_SESSION['reg_id']}',
                nickname = '{$free_memberinfo['nickname']}',
                content = '{$content}',
                profile_image = '{$free_memberinfo['profile_image']}',
                regdate = '".G5_TIME_YMDHIS."'
                ", true);

    $last_uid = sql_insert_id();


    $use_profile = "false";
    $profile_link = "none";

    $my_device_regid = array();

    if($free_memberinfo['sync'] == "Y"){

        $result_my_device = get_device_by_member_id($free_memberinfo['mb_id']);

        for ($i=0; $row_tmp=sql_fetch_array($result_my_device); $i++)
        {
            array_push($my_device_regid, $row_tmp['gpr_reg_id']);
        }  

    }else{
        array_push($my_device_regid, $_SESSION['reg_id']);
    }

    $push_target_regid = array();
    $push_target_mb_id = array();

    // 방에 참여한 모든 회원 리스트 가져오기.
    $member_list_from_room_id = sql_query("SELECT * from g5_gnupushapp_free_member where 1 ");
    for ($i=0; $row_tmp=sql_fetch_array($member_list_from_room_id); $i++)
    {
        if($free_memberinfo['mb_id'] != $row_tmp['mb_id'] && $_SESSION['reg_id'] != $row_tmp['reg_id'] && !in_array($row_tmp['reg_id'],$my_device_regid)){

            if($row_tmp['sync'] == 'Y'){
                array_push($push_target_mb_id,$row_tmp['mb_id']);
            }else{
                array_push($push_target_regid,$row_tmp['reg_id']);
            }
        }
    }

    $pushstyle = "big_text";
    $image_src = "none";

    $title = "(채팅)";
    $ticker = $title;
    $content = ".";
    $address = G5_URL;
    $bottom_text = G5_URL;
    $type = "sms";
    $banner = "headsup";
    $sort = "must";
    $reg_ids = $push_target_regid;
    $etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba1ab&#ba" . $free_memberinfo['nickname'] . "ab&#bafreeab&#ba" . $last_uid;
    if(count($reg_ids) > 0){
        quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "chat");
    }
    if(count($push_target_mb_id) > 0){
        quick_send($push_target_mb_id, $title, $content, $address, $etc, $sort, false, "chat");
    }

    $response = "ok";

}

$array = array("response" => $response);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>