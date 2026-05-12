<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$value = htmlspecialchars($_REQUEST['value']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

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
$count = 0;
$array = array();
$array['response'] = "fail";

if($str_mp == $masterpassword)
{
	$exclude_mb_id = array();

	if($is_member)
	{
		$my_id = $_SESSION['ss_mb_id'];
	}else{
		if($_SESSION['reg_id']){
            $my_device_info = get_device_info_by_regid($_SESSION['reg_id']);
            if($my_device_info['gpr_sync'] == 'Y'){
				$my_id = $my_device_info['gpr_mb_id'];
			}else{
				exit();
			}
		}else{
			exit();
		}
	}
	array_push($exclude_mb_id, $my_id);

	//닉네임과 정확하게 일치
	$row_result = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_nick = '{$value}' ");
	if($row_result['cnt'] > 0)
	{
		$use_profile = "false";
		$profile_link = "none";
		$row_result_val = sql_fetch(" select * from {$g5['member_table']} where mb_nick = '{$value}' ");
		$mb_id = $row_result_val['mb_id'];
		if(!is_array($exclude_mb_id) || !in_array($mb_id, $exclude_mb_id))
		{
			$device_exist = "true";
			$device_count = get_device_count_by_member_id($mb_id);
			if($device_count == 0) $device_exist = "false";

			$mb_icon_url = get_gnu_profile_image($mb_id);
			
			if($mb_icon_url){
				$use_profile = "true";
				$profile_link = $mb_icon_url;
			}
			$thisarray = array("mb_nick" => $value, "use_profile" => $use_profile, "profile_link" => $profile_link, "mb_id" => $mb_id, "device_exist" => $device_exist, "is_friend" => "true");
			$strcount = $count;
			$array["nick".$strcount] = urlencode(json_encode($thisarray));
			$count++;
			array_push($exclude_mb_id, $mb_id);
		}

	}


	//아이디와 정확하게 일치
	$row_result = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_id = '{$value}' ");
	if($row_result['cnt'] > 0)
	{
		$use_profile = "false";
		$profile_link = "none";
		$row_result_val = sql_fetch(" select * from {$g5['member_table']} where mb_id = '{$value}' ");
		$mb_id = $row_result_val['mb_id'];
		if(!is_array($exclude_mb_id) || !in_array($mb_id, $exclude_mb_id))
		{
			$device_exist = "true";
			$device_count = get_device_count_by_member_id($mb_id);
			if($device_count == 0) $device_exist = "false";

			$mb_icon_url = get_gnu_profile_image($mb_id);
			if($mb_icon_url){
				$use_profile = "true";
				$profile_link = $mb_icon_url;
			}
			$thisarray = array("mb_nick" => $row_result_val['mb_nick'], "use_profile" => $use_profile, "profile_link" => $profile_link, "mb_id" => $mb_id, "device_exist" => $device_exist, "is_friend" => "true");
			$strcount = $count;
			$array["nick".$strcount] = urlencode(json_encode($thisarray));
			$count++;
			array_push($exclude_mb_id, $mb_id);
		}

	}


	//값으로 시작하는 닉네임 회원
	$row_result = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_nick like '{$value}%' ");
	if($row_result['cnt'] > 0)
	{
		$row_result2 = sql_query(" select * from {$g5['member_table']} where mb_nick like '{$value}%' ");

		for ($i=0; $row=sql_fetch_array($row_result2); $i++)
		{
			$use_profile = "false";
			$profile_link = "none";
			$mb_id = $row['mb_id'];
			if(!is_array($exclude_mb_id) || !in_array($mb_id, $exclude_mb_id))
			{
				$device_exist = "true";
				$device_count = get_device_count_by_member_id($mb_id);
				if($device_count == 0) $device_exist = "false";

				$mb_icon_url = get_gnu_profile_image($mb_id);
				if($mb_icon_url){
					$use_profile = "true";
					$profile_link = $mb_icon_url;
				}
				$thisarray = array("mb_nick" => $row['mb_nick'], "use_profile" => $use_profile, "profile_link" => $profile_link, "mb_id" => $mb_id, "device_exist" => $device_exist, "is_friend" => "true");
				$strcount = $count;
				$array["nick".$strcount] = urlencode(json_encode($thisarray));
				$count++;
				array_push($exclude_mb_id, $mb_id);
			}
		}

	}


	//값으로 시작하는 아이디 회원
	$row_result = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_id like '{$value}%' ");
	if($row_result['cnt'] > 0)
	{
		$row_result2 = sql_query(" select * from {$g5['member_table']} where mb_id like '{$value}%' ");

		for ($i=0; $row=sql_fetch_array($row_result2); $i++)
		{
			$use_profile = "false";
			$profile_link = "none";
			$mb_id = $row['mb_id'];
			if(!is_array($exclude_mb_id) || !in_array($mb_id, $exclude_mb_id))
			{
				$device_exist = "true";
				$device_count = get_device_count_by_member_id($mb_id);
				if($device_count == 0) $device_exist = "false";
				$mb_icon_url = get_gnu_profile_image($mb_id);
				if($mb_icon_url){
					$use_profile = "true";
					$profile_link = $mb_icon_url;
				}
				$thisarray = array("mb_nick" => $row['mb_nick'], "use_profile" => $use_profile, "profile_link" => $profile_link, "mb_id" => $mb_id, "device_exist" => $device_exist, "is_friend" => "true");
				$strcount = $count;
				$array["nick".$strcount] = urlencode(json_encode($thisarray));
				$count++;
				array_push($exclude_mb_id, $mb_id);
			}
		}

	}


	//값을 포함하는 닉네임 회원
	$row_result = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_nick like '%{$value}%' ");
	if($row_result['cnt'] > 0)
	{
		$row_result2 = sql_query(" select * from {$g5['member_table']} where mb_nick like '%{$value}%' ");

		for ($i=0; $row=sql_fetch_array($row_result2); $i++)
		{
			$use_profile = "false";
			$profile_link = "none";
			$mb_id = $row['mb_id'];
			if(!is_array($exclude_mb_id) || !in_array($mb_id, $exclude_mb_id))
			{
				$device_exist = "true";
				$device_count = get_device_count_by_member_id($mb_id);
				if($device_count == 0) $device_exist = "false";
				$mb_icon_url = get_gnu_profile_image($mb_id);
				
				if($mb_icon_url){
					$use_profile = "true";
					$profile_link = $mb_icon_url;
				}
				$thisarray = array("mb_nick" => $row['mb_nick'], "use_profile" => $use_profile, "profile_link" => $profile_link, "mb_id" => $mb_id, "device_exist" => $device_exist, "is_friend" => "true");
				$strcount = $count;
				$array["nick".$strcount] = urlencode(json_encode($thisarray));
				$count++;
				array_push($exclude_mb_id, $mb_id);
			}
		}

	}


	//값을 포함하는 아이디 회원
	$row_result = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_id like '%{$value}%' ");
	if($row_result['cnt'] > 0)
	{
		$row_result2 = sql_query(" select * from {$g5['member_table']} where mb_id like '%{$value}%' ");

		for ($i=0; $row=sql_fetch_array($row_result2); $i++)
		{
			$use_profile = "false";
			$profile_link = "none";
			$mb_id = $row['mb_id'];
			if(!is_array($exclude_mb_id) || !in_array($mb_id, $exclude_mb_id))
			{
				$device_exist = "true";
				$device_count = get_device_count_by_member_id($mb_id);
				if($device_count == 0) $device_exist = "false";

				$mb_icon_url = get_gnu_profile_image($mb_id);
				if($mb_icon_url){
					$use_profile = "true";
					$profile_link = $mb_icon_url;
				}
                $thisarray = array("mb_nick" => $row['mb_nick'], "use_profile" => $use_profile, "profile_link" => $profile_link, "mb_id" => $mb_id, "device_exist" => $device_exist, "is_friend" => "true");
				$strcount = $count;
				$array["nick".$strcount] = urlencode(json_encode($thisarray));
				$count++;
				array_push($exclude_mb_id, $mb_id);
			}
		}

	}

	if($count > 0)
	{
		$array['response'] = "ok";
	}
	else
	{
		$array['response'] = "none";
	}

	$array['count'] = $count;

}

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>