<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');

$gnu_config = get_gnupushapp_config();

$reg_id = htmlspecialchars($_POST['reg_id']);
$masterpassword = htmlspecialchars($_POST['masterpassword']);
$error = urldecode(htmlspecialchars($_POST['error']));

$str_mp = substr($gnu_config['masterpassword'], 0, 15);

if($str_mp == $masterpassword)
{
	import_Error_device($reg_id,$error);
}

exit();

?>