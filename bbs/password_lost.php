<?php
include_once('./_common.php');

include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if ($is_member) {
    alert_close("이미 로그인중입니다.");
}

$g5['title'] = '회원정보 찾기';
include_once(G5_PATH.'/head.sub.php');

if(!$types) $types = "hp";


if($types == "hp") {
$action_url = G5_HTTPS_BBS_URL."/password_lost3.php";	
include_once($member_skin_path.'/password_hp.skin.php');
}else{
$action_url = G5_HTTPS_BBS_URL."/password_lost2.php";
include_once($member_skin_path.'/password_lost.skin.php');
}
include_once(G5_PATH.'/tail.sub.php');
?>