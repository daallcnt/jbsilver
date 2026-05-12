<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$gnu_config = get_gnupushapp_config();

$room = htmlspecialchars($_REQUEST['room_id']);
$value = htmlspecialchars($_REQUEST['value']);
$key = htmlspecialchars($_REQUEST['key']);
$is_file = htmlspecialchars($_REQUEST['is_file']);
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
$last_uid = "0";
$room_type = "onetoone";

$delete_cnt = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios WHERE gpci_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ");
if($delete_cnt['cnt'] > 0)
{
	sql_query("DELETE FROM g5_gnupushapp_chatroom_ios WHERE gpci_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ", true);
}

$in_is_member = "N";
$my_id = "";

if($str_mp == $masterpassword)
{

    $my_device_info = get_device_info_by_regid($_SESSION['reg_id']);
    //비회원일 경우 임의로 만든 아이디를 부여하기 위해 미리 준비함.
	$temp_my_id = substr($_SESSION['reg_id'], 30, 18);

	if($is_member)
	{
        $in_is_member = "Y";
		$my_id = $_SESSION['ss_mb_id'];
		$my_member_info = get_member($my_id);
		$nick_name = $my_member_info['mb_nick'];
	}else{

		if($_SESSION['reg_id']){
            if($my_device_info['gpr_sync'] == 'Y'){
                $in_is_member = "Y";
                $my_id = $my_device_info['gpr_mb_id'];
                $my_member_info = get_member($my_id);
                $nick_name = $my_member_info['mb_nick'];

            }else{
                $my_id = $temp_my_id;
			    $nick_name = "user" . substr($temp_my_id, 8, 6);
            }
		}else{

			$response = "fail";
			$array = array("response" => $response);
			$json = "";
			$json = json_encode($array);
			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();
		}
	}

    $error = false;

    $file_name_random = "none";

    $is_image = "N";

    if(!$is_file)
    {
        $is_file = "N";
    }

    // filename 가져오기
    if($is_file == "Y")
    {
        if($gnu_config['chatting_file'] != 'N')
        {
            $file_info = $_FILES['Filedata'];
            if(preg_match('/^=\?UTF-8\?B\?(.+)\?=$/i', $file_info['name'], $match))
            {
                $file_name_temp = base64_decode(strtr($match[1], ':', '/'));
            }
            $value = get_safe_filename($file_name_temp);

            $tmp_file  = $file_info['tmp_name'];
            $filesize  = $file_info['size'];
            $filename  = $file_name_temp;

            if ($filename) {
                if ($file_info['error'] == 1) {
                    $error = true;
                    $error_m = "파일업로드에 실패하였습니다.";
                }
                else if ($file_info['error'] != 0) {
                    $error = true;
                    $error_m = "파일업로드에 실패하였습니다.";
                }
            }

            if (is_uploaded_file($tmp_file)) {
                // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
                if (!$is_admin && $filesize > 10485760 )
                {
                    $error = true;
                    $error_m = "허용된 용량을 초과하였습니다(10M 이하만 가능).";
                }

                if(preg_match("/(\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc))$/i", $file_name_temp))
                {
                    $error = true;
                    $error_m = "금지된 파일형식입니다.";
                }
                
                if (preg_match("/(\.(jpg|jpeg|gif|png))$/i", $file_name_temp))
                {
                    $is_image = "Y";
                }

                if (!preg_match("/(\.(jpg|jpeg|gif|png))$/i", $file_name_temp))
                {
                    if($gnu_config['chatting_file'] != 'A')
                    {
                        $error = true;
                        $error_m = "이미지만 업로드하실 수 있습니다.";
                    }
                }
            }

            $ext = substr($filename, -4);
            if(strtolower($ext) == 'jpeg') $ext = '.'.$ext;

            $file_random_name = get_random_string_gnu(30);

            $file_name_random = $file_random_name . $ext;
        }
        else
        {
            $error = true;
            $error_m = "파일 업로드가 불가합니다.";
        }

    }

    if($error)
    {
        $response = "error";
        $array = array("response" => $response, "error_m" => $error_m);

        $json = "";

        $json = json_encode($array);

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Length: ' . mb_strlen($json));
        echo $json;
        exit();

    }
    else
    {

        if($is_file == "N")
        {
            $content = htmlspecialchars(urldecode(base64_decode($value)));
        }
        else
        {
            $content = $value;
        }

        sql_query(" INSERT INTO g5_gnupushapp_newchatting_content 
                    set cr_ix = '{$room}',
                    is_member = '{$in_is_member}',
                    mb_id = '{$my_id}',
                    reg_id = '{$_SESSION['reg_id']}',
                    nick_name = '{$nick_name}',
                    content = '{$content}',
                    is_file = '{$is_file}',
                    is_image = '{$is_image}',
                    filename = '{$content}',
                    filepath = '{$file_name_random}',
                    c_status = 'ok',
                    is_readed = 'N',
                    read_date = '".G5_TIME_YMDHIS."',
                    regdate = '".G5_TIME_YMDHIS."'
                    ", true);

        $last_uid = sql_insert_id();

        //첨부파일 재처리
        if($is_file == "Y")
        {

            $file_dir = G5_DATA_PATH.'/gnupushchat/'.$last_uid;
            $mb_image_path = $file_dir . '/'  . $file_name_random;
            if(file_exists($mb_image_path)){
                @unlink($mb_image_path);
            }

            if(!is_dir($file_dir)) {
                @mkdir($file_dir, G5_DIR_PERMISSION,true);
                @chmod($file_dir, G5_DIR_PERMISSION,true);
            }
            move_uploaded_file($tmp_file, $mb_image_path);
            chmod($mb_image_path, G5_FILE_PERMISSION);

        }


        $use_profile = "false";
        $profile_link = "none";

        $my_device_regid = array();

        if($in_is_member == "Y"){
            $mb_icon_url = get_gnu_profile_image($my_id);
            
            if($mb_icon_url){
                $use_profile = "true";
                $profile_link = $mb_icon_url;
            }

            $result_my_device = get_device_by_member_id($my_id);

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
        $member_list_from_room_id = sql_query("select * from g5_gnupushapp_newchatting_room_joinlist where cr_ix = '{$room}' and (c_status = 'join' or c_status = 'nowjoin') ");
        for ($i=0; $row_tmp=sql_fetch_array($member_list_from_room_id); $i++)
	    {
            if($my_id != $row_tmp['mb_id'] && $_SESSION['reg_id'] != $row_tmp['reg_id'] && !in_array($row_tmp['reg_id'],$my_device_regid)){

                if($row_tmp['is_member'] == 'Y'){
                    array_push($push_target_mb_id,$row_tmp['mb_id']);
                }else{
                    array_push($push_target_regid,$row_tmp['reg_id']);
                }
                // read db에 방 member 전부 일괄적으로 넣기...
                sql_query(" INSERT INTO g5_gnupushapp_newchatting_content_readlist 
                                set cr_ix = '{$room}',
                                cc_ix = '{$last_uid}',
                                is_member = '{$row_tmp['is_member']}',
                                mb_id = '{$row_tmp['mb_id']}',
                                reg_id = '{$row_tmp['reg_id']}',
                                is_readed = 'N',
                                read_date = '".G5_TIME_YMDHIS."',
                                regdate = '".G5_TIME_YMDHIS."'
                                ", true);
            }
        }

        $pushstyle = "big_text";
        $image_src = "none";

        if($is_file == "Y" && $is_image == "Y")
        {
            if(file_exists($mb_image_path)){

                $fileDate = date("YmdHms",strtotime(G5_TIME_YMDHIS));

                $filename = $fileDate . $file_name_temp;

                $size = @getimagesize($mb_image_path);

                $target_path = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$last_uid;

                if(!is_dir($target_path)) {
                    @mkdir($target_path, G5_DIR_PERMISSION,true);
                    @chmod($target_path, G5_DIR_PERMISSION,true);
                }

                $newfile = $target_path. '/' . $filename;

                if (copy($mb_image_path, $newfile)) {

                    $thumb_filename = basename($newfile);
                    $filepath = dirname($newfile);

                    $width=500;
                    $height=250;

                    $thumb = thumbnail($thumb_filename, $filepath, $filepath, $width, $height, false, true, 'center', false, $um_value='80/0.5/3');

                    if(file_exists($newfile)){
                        @unlink($newfile);
                    }

                    $pushstyle = "big_picture";
                    $image_src = str_replace(G5_PATH, G5_URL, $filepath.'/'.$thumb);
                }
            }
        }

        $title = "(채팅) " . $nick_name . "님";
        $ticker = $title;
        $content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;', '#8@plus#8@'), array('<', '>', '"', ' ', '&', '+'), $content);
        $content = cut_str(strip_tags($content), 200, '...');
        $address = G5_URL;
        $bottom_text = G5_URL;
        $type = "sms";
        $banner = "headsup";
        $sort = "must";
        $reg_ids = $push_target_regid;
        $etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $room . "ab&#ba" . $nick_name . "ab&#ba" . $room_type . "ab&#ba" . $last_uid;
        if(count($reg_ids) > 0){
            quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "chat");
        }
        if(count($push_target_mb_id) > 0){
            quick_send($push_target_mb_id, $title, $content, $address, $etc, $sort, false, "chat");
        }

        if($is_file == "Y")
        {
            $fileDate = date("YmdHms",strtotime(G5_TIME_YMDHIS));

            $filename = $fileDate . $content;

            $file_link = G5_DATA_URL.'/gnupushchat/'.$last_uid ."/". $file_name_random;

            if($is_image == "Y")
            {
                $file_dir = G5_DATA_PATH.'/gnupushchat/'.$last_uid;
                $mb_image_path = $file_dir . '/'  . $file_name_random;
                if(file_exists($mb_image_path)){
                    $size = @getimagesize($mb_image_path);

                    $target_path = $file_dir = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$last_uid;

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

    }

}

$array = array("response" => $response, "keyvalue" => strval($last_uid), "file_link" => $file_link);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>