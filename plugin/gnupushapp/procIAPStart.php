<?php
include_once('./_common.php');

$inapp_type = htmlspecialchars($_POST['type']);
$product_id = htmlspecialchars($_POST['product_id']);
$money = htmlspecialchars($_POST['money']);
$regid = htmlspecialchars($_POST['regid']);

if($is_member && $secret_inapp = get_session('secret_inapp')) {

	$member_id = $_SESSION['ss_mb_id'];

	set_session('inapp_product_id', $product_id);
	set_session('inapp_money', $money);
	set_session('inapp_type', $inapp_type);

	sql_query(" INSERT INTO g5_gnupushapp_inapp 
					set gin_regdate = '".G5_TIME_YMDHIS."',
					gin_reg_id = '$regid',
					gin_mb_id = '$member_id',
					gin_secret = '$secret_inapp',
					gin_product_id = '$product_id',
					gin_status = 'start',
					gin_money = '$money',
					gin_type = '$inapp_type'
					", true);

	echo "ok";

}else{
	echo "fail";
}

?>