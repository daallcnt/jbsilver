<?php
include_once('./_common.php');

$fpa = htmlspecialchars($_REQUEST['fpa']);

set_session('pushappfirstA', "true");
if(preg_match("/GNUPUSHIPHO/", $_SERVER['HTTP_USER_AGENT'])) {
	goto_url(G5_URL."/plugin/gnupushapp/iphone_script.php", false);
}else{
	if($fpa){
		goto_url(G5_URL."/plugin/gnupushapp/iphone_script.php", false);
	}else{
		goto_url(G5_URL."/?gnupushapp=ok", false);
	}
}

?>