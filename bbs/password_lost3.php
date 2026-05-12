<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if ($is_member) {
    alert_close('이미 로그인중입니다.');
}

$mb_no           = trim($_POST['mb_no']);
$mb_hp           = trim($_POST['mb_hp']);
$mb_datetime     = trim($_POST['mb_datetime']);
$mb_hp_certify = trim($_POST['mb_hp_certify']);

// 핸드폰 번호와 인증번호가 같이 넘어 왔다면 
if ($mb_hp && $mb_hp_certify) { 
    // 인증번호가 같다면 
    if (get_session("ss_hp_certify_number") == $mb_hp_certify) { 
		set_session("ss_hp_certify_number", ""); // 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다. 
		// 회원아이디가 아닌 회원고유번호로 회원정보를 구한다.
		$sql = " select mb_id, mb_datetime, mb_lost_certify, mb_name from {$g5['member_table']} where mb_no = '$mb_no' ";
		$mb  = sql_fetch($sql);
		if (!trim($mb['mb_lost_certify'])) 
			alert("인증처리 오류!!");

		// 인증 링크는 한번만 처리가 되게 한다.
		sql_query(" update {$g5['member_table']} set mb_lost_certify = '' where mb_no = '$mb_no' ");

		// 변경될 패스워드가 넘어와야하고 저장된 변경패스워드를 md5 로 변환하여 같으면 정상
		if ($mb_hp_certify && $mb_datetime === sql_password($mb['mb_datetime']) && $mb_hp_certify === $mb['mb_lost_certify']) {

/*// 난수 발생
srand(time());
$randval = rand(4, 6); 
$change_password = substr(md5(get_microtime()), 0, $randval);
*/
$change_password = $mb['mb_lost_certify'];
$mb_lost_certify = sql_password($change_password);		
			sql_query(" update {$g5['member_table']} set mb_password = '$mb_lost_certify' where mb_no = '$mb_no' ");
			//alert("휴대폰으로 보내드린 인증번호가 패스워드로 변경 되었습니다.\\n\\n회원아이디와 변경된 패스워드로 로그인 하시기 바랍니다.", "$g4[url]/$g4[bbs]/login.php");
		}else {
			alert("정상적인 접근이 아닌것 같습니다.");
		}

    }else{
		alert("잘못된 인증번호입니다.");
	} 
}else{
	alert("정상적인 접근이 아닌것 같습니다.");
}



$g5['title'] = '회원정보 찾기';
include_once(G5_PATH.'/head.sub.php');

include_once($member_skin_path.'/password_hp_confirm.skin.php');

include_once(G5_PATH.'/tail.sub.php');
?>
