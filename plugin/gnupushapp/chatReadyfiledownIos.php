<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();
$masterpassword = htmlspecialchars($_POST['masterpassword']);
$cc_ix = htmlspecialchars($_POST['key']);
$random = htmlspecialchars($_POST['random']);

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

$response = "fail";
$filedown_id = "none";
$filename = "none";
$delete_cnt = sql_fetch(" select count(*) as 'cnt' from g5_gnupushapp_chatroom_ios WHERE gpci_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ");
if($delete_cnt['cnt'] > 0)
{
	sql_query("DELETE FROM g5_gnupushapp_chatroom_ios WHERE gpci_lastdate < date_add(date_format( now() , '%Y-%m-%d %k:%i:%s'), interval -10 second) ", true);
}

if($str_mp == $masterpassword && $_SESSION['reg_id'])
{

	$row_tmp = sql_fetch(" select * from g5_gnupushapp_chatroom_ios where gpci_reg_id = '{$_SESSION['reg_id']}' and gpci_random = '{$random}' ");

	if($row_tmp['cr_ix'])
	{

                $row_tmp2 = sql_fetch(" select * from g5_gnupushapp_newchatting_content where cr_ix = '{$row_tmp['cr_ix']}' and cc_ix = '{$cc_ix}' ");

                if($row_tmp2['content']){
                        $response = "ok";

                        $filename = $row_tmp2['content'];
                
                        $recentDate = date("YmdHms",strtotime($row_tmp2['regdate']));
                
                        $filename = $recentDate . $filename;
                
                        $file_path_name = $row_tmp2['filepath'];
                
                        $rnum = get_random_string_gnu(30);
                        $filedown_id = date('Ymd') . $rnum;
                
                        sql_query(" INSERT INTO g5_gnupushapp_filedown 
                                set ggf_keypass = '$filedown_id',
                                ggf_bo_table = 'none',
                                ggf_wr_id = '1',
                                ggf_no = '1',
                                ggf_chatOFN = '{$file_path_name}',
                                ggf_chatFN = '{$filename}',
                                ggf_chat_ix = '{$cc_ix}',
                                ggf_downloadok = 'N',
                                ggf_regdate = '".G5_TIME_YMDHIS."'
                                ", true);
                }
	}
}

$array = array("response" => $response, "filedown_id" => $filedown_id, "filename" => $filename);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();
?>

