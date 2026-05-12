<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$pass = htmlspecialchars($_REQUEST['pass']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";
$success_message = "none";

if($str_mp == $masterpassword && $reg_id == $_SESSION['reg_id'] && $is_member && $pass == $_SESSION['pass_IAP'] && $_SESSION['secret_inapp'] && $_SESSION['inapp_product_id'] && $_SESSION['inapp_money'] && $_SESSION['inapp_type'])
{
	$product_id = $_SESSION['inapp_product_id'];
	$money = $_SESSION['inapp_money'];
	$secret_inapp = $_SESSION['secret_inapp'];
	$inapp_type = $_SESSION['inapp_type'];
	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
		$my_id = $_SESSION['ss_mb_id'];
		if($row_1['gpr_mb_id'] == $my_id)
		{
			$sql = " select count(*) as cnt from g5_gnupushapp_inapp where gin_reg_id = '$reg_id' and gin_mb_id = '$my_id' and gin_secret = '$secret_inapp' and gin_product_id = '$product_id' and gin_money = '$money' and gin_type = '$inapp_type' and gin_status = 'start' ";
			$row_tmp_reg_id = sql_fetch($sql);
			
			if($row_tmp_reg_id['cnt'] > 0){
				
				$row_tmp_reg_id = sql_fetch(" select * from g5_gnupushapp_inapp 
											where gin_reg_id = '{$reg_id}' 
											and gin_mb_id = '$my_id' 
											and gin_secret = '$secret_inapp'
											and gin_product_id = '$product_id'
											and gin_money = '$money'
											and gin_type = '$inapp_type'
											and gin_status = 'start' ");

				$sql = " update g5_gnupushapp_inapp
							set gin_status = 'complete'
							where gin_ix = '{$row_tmp_reg_id['gin_ix']}' ";
				sql_query($sql);
				$success_message = iap_success();
				$response = "ok";
				
			}
			
		}
	}
}

set_session('pass_IAP', '');
set_session('secret_inapp', '');
set_session('inapp_product_id', '');
set_session('inapp_money', '');
set_session('inapp_type', '');

$array = array("response" => $response, "message" => $success_message);

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>