<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_REQUEST['reg_id']);
$masterpassword = htmlspecialchars($_REQUEST['masterpassword']);
$inapp_secret = htmlspecialchars($_REQUEST['inapp_secret']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

$response = "fail";
$requestPass1 = "none";
$requestPass2 = "none";
$requestPass3 = "none";

if($str_mp == $masterpassword && $reg_id && $reg_id2 = get_session('reg_id') && $is_member && $inapp_secret == $_SESSION['secret_inapp'])
{
	$row_1 = get_device_info_by_regid($reg_id);
	if($row_1)
	{
		$my_id = $_SESSION['ss_mb_id'];
		if($row_1['gpr_mb_id'] == $my_id && $reg_id == $reg_id2)
		{
			$requestPass1 = mt_rand(0,20);
			$requestPass2 = mt_rand(25,45);
			$requestPass3 = mt_rand(50,70);

			$requestPass1_val = substr($gnu_config['iap_key'], $requestPass1, 4);
			$requestPass2_val = substr($gnu_config['iap_key'], $requestPass2, 4);
			$requestPass3_val = substr($gnu_config['iap_key'], $requestPass3, 4);

			set_session('pass_IAP', $requestPass1_val.$requestPass2_val.$requestPass3_val);

			$response = "ok";
		}
	}

}

$array = array("response" => $response, "requestPass1" => strval($requestPass1), "requestPass2" => strval($requestPass2), "requestPass3" => strval($requestPass3));

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;
exit();

?>