<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);
$cr_ix = htmlspecialchars($_REQUEST['room_id']);
$cc_ix = htmlspecialchars($_REQUEST['cc_ix']);

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

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$array = array();
$my_id = "none";

if($str_mp == $masterpassword && $_SESSION['reg_id'])
{
    $device_in = get_device_info_by_regid($_SESSION['reg_id']);

    //비회원일 경우 임의로 만든 아이디를 부여하기 위해 미리 준비함.
	$temp_my_id = substr($_SESSION['reg_id'], 30, 18);
    
    //방정보 가져오기...
    $room_info = sql_fetch("select * from g5_gnupushapp_newchatting_room where cr_ix = '$cr_ix' ");
    

	if($is_member){
		$my_id = $_SESSION['ss_mb_id'];
	}else{
		if($device_in['gpr_sync'] == "N" && (($gnu_config['chatting_admin'] == "D" && $room_info['type'] == 'inquire') || $gnu_config['chatting_nonmembers'] == "Y") )
        {
            $my_id = $temp_my_id;
        }
        else
        {
            $array["response"] = "fail";
			$json = json_encode($array);

			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . mb_strlen($json));
			echo $json;
			exit();
        }
    }

    $target_profile = "false";
    $target_profile_link = "none";

    $array_mb_ids = explode(",",$room_info['join_list']);
    foreach($array_mb_ids as $val){
        if($val != $my_id) $target_mb_id = $val;
    }

    $mb_icon_url = get_gnu_profile_image($target_mb_id);
    if($mb_icon_url){
        $target_profile = "true";
        $target_profile_link = $mb_icon_url;
    }

    $row_result = sql_query(" select * from g5_gnupushapp_newchatting_content where cr_ix = '$cr_ix' and cc_ix > $cc_ix and c_status = 'ok' order by regdate desc ");

	$count = 0;

	$array_pre = array();

	for ($i=0; $row_tmp=sql_fetch_array($row_result); $i++)
	{		
        $is_readed = $row_tmp['is_readed'];

        $content = str_replace(array('&lt;', '&gt;', '&quot;', '&nbsp;', '&amp;'), array('<', '>', '"', ' ', '&'), $row_tmp['content']);
        if($device_in['gpr_sort'] == "I")
        {
            $content = str_replace('+', '#8@plus#8@', $content);
        }
        else
        {
            $content = str_replace('#8@plus#8@', '+', $content);
        }

        $regdate = date('Y-m-d', strtotime($row_tmp['regdate']));

        $time_h = date("H",strtotime($row_tmp['regdate']));
        $time_m = date("i",strtotime($row_tmp['regdate']));
        if($time_h > 12)
        {
            $time_h = $time_h - 12;
            $time = "오후 " . $time_h . ":" . $time_m;
        }
        else
        {
            $time = "오전 " . $time_h . ":" . $time_m;
        }

        $is_file = $row_tmp['is_file'];
        $is_image = $row_tmp['is_image'];

        $file_link = "";

        $file_origin_link = "";

        if($is_file == "Y")
        {
            $fileDate = date("YmdHms",strtotime($row_tmp['regdate']));

            $filename = $fileDate . $row_resrow_tmpult_a['content'];
            
            $file_link = G5_DATA_URL.'/gnupushchat/'.$row_tmp['cc_ix'] ."/". $row_tmp['filepath'];

            $file_origin_link = $file_link;

            if($is_image == "Y")
            {
                $file_dir = G5_DATA_PATH.'/gnupushchat/'.$row_tmp['cc_ix'];
                $mb_image_path = $file_dir . '/'  . $row_tmp['filepath'];
                if(file_exists($mb_image_path)){
                    $size = @getimagesize($mb_image_path);

                    $target_path = $file_dir = G5_DATA_PATH.'/gnupushchat/thumbnail/'.$row_tmp['cc_ix'];

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

        $thisarray = array(
            "key" => $row_tmp['cc_ix'],
            "send_mb_id" => $row_tmp['mb_id'],
            "content" => $content,
            "send_time" => $time,
            "is_readed" => $is_readed,
            "date" => $regdate,
            "is_file" => $is_file,
            "is_image" => $is_image,
            "file_link" => $file_link,
            "file_origin_link" => $file_origin_link
        );

        $arrayval = urlencode(json_encode($thisarray));

        array_push($array_pre, $arrayval);
		
		$count++;
	}

	if($count == 0)
	{
		$array = array("response" => "none", "target_profile" => $target_profile, "target_profile_link" => $target_profile_link);
	}
	else
	{
        //읽음 처리, 푸시알림 처리.
        $to_reg_id = false;

        $target_member_info = get_member($target_mb_id);

        if(!$target_member_info['mb_id'] && $gnu_config['chatting_admin_id'] == $my_id){

            $reg_id_result = sql_fetch(" select reg_id from g5_gnupushapp_newchatting_room_joinlist where mb_id <> '$my_id' and cr_ix = '$cr_ix' and (c_status = 'join' or c_status = 'nowjoin') ");
            $target_reg_id = $reg_id_result['reg_id'];
            $to_reg_id = true;
        }

        //푸시알림 발송
        $use_profile = "false";
        $profile_link = "none";
        $pushstyle = "big_text";
        $image_src = "none";
        $title = "none";
        $ticker = $title;
        $content = "none";
        $address = G5_URL;
        $bottom_text = G5_URL;
        $type = "sms";
        $banner = "headsup";
        $sort = "must";
        $etc = $use_profile . "ab&#ba" . $profile_link . "ab&#ba" . $pushstyle . "ab&#ba" . $image_src . "ab&#ba" . $ticker . "ab&#ba" . $bottom_text . "ab&#ba" . $type . "ab&#ba" . $banner . "ab&#ba" . $cr_ix;
        if($to_reg_id){
            $reg_ids = array($target_reg_id);
            quick_send_regids($reg_ids, $title, $content, $address, $etc, $sort, false, "read");
        }else{
            $member_ids = array($target_mb_id);
            quick_send($member_ids, $title, $content, $address, $etc, $sort, false, "read");
        }


		
		$array = array("response" => "ok", "target_profile" => $target_profile, "target_profile_link" => $target_profile_link);

		$array["count"] = $count;

		$array_reverse = array_reverse($array_pre);

		for($i=0;$i<$count;$i++)
		{
			$array["chat".$i] = $array_reverse[$i];
		}
	}

	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;
	exit();


}
else
{
	$array["response"] = "fail";
	$json = json_encode($array);

	header('Content-Type: application/json; charset=utf-8');
	header('Content-Length: ' . mb_strlen($json));
	echo $json;
	exit();
}



?>