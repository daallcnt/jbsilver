<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');

$gnu_config = get_gnupushapp_config();

$keypass = htmlspecialchars($_POST['keypass']);
$masterpassword = htmlspecialchars($_POST['masterpassword']);

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword && $keypass)
{
	sql_query("UPDATE g5_gnupushapp_push set gp_response = gp_response + 1 where gp_pushid = '{$keypass}' ");
}

$array = array("response" => "ok");

$json = "";

$json = json_encode($array);

header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . mb_strlen($json));
echo $json;

exit();
?>