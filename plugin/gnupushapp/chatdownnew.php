<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$cc_ix = htmlspecialchars($_REQUEST['cc_ix']);
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
$use_profile = "false";
$profile_link = "none";
$content = "";
$mb_nick = "";
$time = "";
$recentDate = "0000-00-00";
$is_file = "N";
$is_image = "N";
$file_link = "none";
$to_reg_id = false;
$file_origin_link = "";
if($str_mp == $masterpassword)
{
    $device_in = get_device_info_by_regid($_SESSION['reg_id']);
    $row_result_a = sql_fetch(" select * from g5_gnupushapp_newchatting_content where cc_ix = '$cc_ix' ");

    if(!$_SESSION['ss_mb_id']){
        if(($gnu_config['chatting_admin'] == "D" && $row_result_a['mb_id'] == $gnu_config['chatting_admin_id'] && $device_in['gpr_sync'] == "N") || $gnu_config['chatting_nonmembers'] == "Y"){
            $my_id = substr($_SESSION['reg_id'], 30, 18);
        }
    }else{
        $my_id = $_SESSION['ss_mb_id'];
    }
    
    $row_result_d = sql_fetch(" select count(*) as cnt from g5_gnupushapp_newchatting_room_joinlist where cr_ix = '{$row_result_a['cr_ix']}' and mb_id = '{$my_id}' and c_status in ('join','nowjoin')");
    if($row_result_d['cnt'] > 0){
        $content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $row_result_a['content']);
        if($device_in['gpr_sort'] == "A"){
            $content = str_replace('#8@plus#8@', '+', $content);
        }
        if($device_in['gpr_sort'] == "I"){
            $content = str_replace('+', '#8@plus#8@', $content);
        }

        $mb_nick = $row_result_a['nick_name'];

        $target_member_info = get_member($row_result_a['mb_id']);
        if($target_member_info['mb_id']){
            $mb_nick = $target_member_info['mb_nick'];
        }else{
            $to_reg_id = true;
            $target_reg_id = $row_result_a['reg_id'];
        }
        

        $time_h = date("H",strtotime($row_result_a['regdate']));
        $time_m = date("i",strtotime($row_result_a['regdate']));
        if($time_h > 12)
        {
            $time_h = $time_h - 12;
            $time_input = "오후 " . $time_h . ":" . $time_m;
        }
        else
        {
            $time_input = "오전 " . $time_h . ":" . $time_m;
        }
        
        $time = $time_input;
        $recentDate = date("Y-m-d", strtotime( $row_result_a['regdate'] ) );

        $is_file = $row_result_a['is_file'];
        $is_image = $row_result_a['is_image'];

        if($row_result_a['is_member']){
            $mb_icon_url = get_gnu_profile_image($row_result_a['mb_id']);
			
			if($mb_icon_url){
				$use_profile = "true";
				$profile_link = $mb_icon_url;
			}
        }

        if($is_file == "Y")
        {
            $fileDate = date("YmdHms",strtotime($row_result_a['regdate']));

            $filename = $fileDate . $row_result_a['content'];

            $file_link = G5_DATA_URL.'/gnupushchat/'.$row_result_a['cc_ix'] ."/". $row_result_a['filepath'];
            $file_origin_link = $file_link;

            if($row_result_a['is_image'] == "Y")
            {
                $file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_result_a['cc_ix'];
                $mb_image_path = $file_dir . '/'  . $row_result_a['filepath'];
                if(file_exists($mb_image_path)){
                    $size = @getimagesize($mb_image_path);

                    $target_path = $file_dir = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$row_result_a['cc_ix'];

                    if(!is_dir($target_path)) {
                        @mkdir($target_path, G5_DIR_PERMISSION,true);
                        @chmod($target_path, G5_DIR_PERMISSION,true);
                    }

                    $newfile = $target_path. '/' . $filename;

                    if (copy($mb_image_path, $newfile)) {

                        $thumb_filename = basename($newfile);
                        $filepath = dirname($newfile);

                        if($size[0] > 500)
                        {
                            $size_y = round(( $size[1] * 450 ) / $size[0]);
                            $thumb = thumbnail($thumb_filename, $filepath, $filepath, 450, $size_y, false, false, 'center', false, $um_value='80/0.5/3');
                        }
                        else
                        {
                            $thumb = thumbnail($thumb_filename, $filepath, $filepath, $size[0], $size[1], false, false, 'center', false, $um_value='80/0.5/3');
                        }

                        if(file_exists($newfile)){
                            @unlink($newfile);
                        }

                        $file_link = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
                    }
                }
            }
        }
        $response = "ok";

        //읽음으로 변경 / 글작성에게 푸시알림.
        sql_query("update g5_gnupushapp_newchatting_content_readlist set is_readed = 'Y', read_date = '".G5_TIME_YMDHIS."' where cr_ix = '{$row_result_a['cr_ix']}' and mb_id = '$my_id' and is_readed = 'N'");
			
        //해당 방에서 안읽음이 없을 경우 
        $read_result_total_count = sql_fetch("select count(*) as cnt from g5_gnupushapp_newchatting_content_readlist where cr_ix = '{$row_result_a['cr_ix']}' and is_readed = 'N'");
        if($read_result_total_count['cnt'] == 0){
            sql_query("update g5_gnupushapp_newchatting_content set is_readed = 'Y' where cr_ix = '{$row_result_a['cr_ix']}' ");
        }

        //푸시알림 발송
        $use_profile = "false";
        $profile_link = "none";
        $pushstyle = "big_text";
        $image_src = "none";
        $title = "none";
        $ticker = $title;
        $content_push = "none";
        $address = G5_URL;
        $bottom_text = G5_URL;
        $type = "sms";
        $banner = "headsup";
        $sort = "must";
        $etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $row_result_a['cr_ix'];
        if($to_reg_id){
            $reg_ids = array($target_reg_id);
            quick_send_regids($reg_ids, $title, $content_push, $address, $etc, $sort, false, "read");
        }else{
            $member_ids = array($target_member_info['mb_id']);
            quick_send($member_ids, $title, $content_push, $address, $etc, $sort, false, "read");
        }
    }
}

$array = array("response" => $response, "nick_name" => $mb_nick, "time" => $time, "content" => $content, "use_profile" => $use_profile, "profile_link" => $profile_link, "date" => $recentDate, "is_file" => $is_file, "is_image" => $is_image, "file_link" => $file_link, "file_origin_link" => $file_origin_link);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>