<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/gnupushapp.lib.php');
$gnupushapp_email = get_session('gnupushapp_email');

$array_e = explode("/",$gnupushapp_email);
$old_email = $array_e[0];
$mb_email = $array_e[1];

set_session('gnupushapp_email', '');

if ($old_email != $mb_email && $config['cf_use_email_certify']) {
	set_session('ss_mb_id', '');
	alert('회원 정보가 수정 되었습니다.\n\nE-mail 주소가 변경되었으므로 다시 인증하셔야 합니다.', G5_URL);
} else {
	goto_url(G5_URL);
}


?>