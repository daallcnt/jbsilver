<?php
include_once('./_common.php');

if ($_GET["token"] && get_session("ss_token") == $_GET["token"]) { 
    set_session("ss_token", ""); // 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다. 
} else { 
    alert_close("인증번호 발송시 오류가 발생하였습니다.\\n\\n새로고침 후 다시 시도하시기 바랍니다."); 
    exit; 
} 

if ($is_member) {
    alert_close("이미 로그인중입니다.");
}

$hp = trim($_GET['mb_hp']);
$name = trim($_GET['mb_name']);

if (!$hp) 
    alert_close("휴대폰번호 오류입니다.");

$receive_number = preg_replace("/[^0-9]/", "", $hp); // 수신자번호 

    $sql = "select count(*) as cnt from {$g5['member_table']} where replace(mb_hp,'-','') = '$receive_number' and mb_name = '$name' ";
    $row = sql_fetch($sql);
	if ($row['cnt'] > 1)
    alert_close("동일한 휴대폰 번호가 2개 이상 존재합니다.\\n\\n관리자에게 문의하여 주십시오.");


$sql = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_hp, mb_datetime from {$g5['member_table']} where replace(mb_hp,'-','') = '$receive_number' and mb_name = '$name' ";
$mb = sql_fetch($sql);

if (!$mb['mb_id']) 
    alert_close("존재하지 않는 회원입니다.");
else if (is_admin($mb['mb_id'])) 
    alert_close("관리자 아이디는 접근 불가합니다.");


// SMS BEGIN -------------------------------------------------------- 
if ($w == "" && $receive_number)
{

$send_number = preg_replace("/[^0-9]/", "", $sms5['cf_phone']); // 발신자번호
$certify_number = rand(100000, 999999); 

$sms_contents = ""; 
$sms_contents .= $certify_number; 
$sms_contents .= "\n\n인증번호 입니다.";

	if ($config['cf_sms_use'] == 'icode')
	{
		if($config['cf_sms_type'] == 'LMS') {
            include_once(G5_LIB_PATH.'/icode.lms.lib.php');

            $port_setting = get_icode_port_type($config['cf_icode_id'], $config['cf_icode_pw']);

            // SMS 모듈 클래스 생성
            if($port_setting !== false) {
                $SMS = new LMS;
                $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $port_setting);

                $strDest     = array();
                $strDest[]   = $receive_number;
                $strCallBack = $send_number;
                $strCaller   = iconv_euckr(trim($default['de_admin_company_name']));
                $strSubject  = '';
                $strURL      = '';
                $strData     = iconv_euckr($sms_contents);
                $strDate     = '';
                $nCount      = count($strDest);

                $res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);

                $SMS->Send();
                $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
            }
        } else {
            include_once(G5_LIB_PATH.'/icode.sms.lib.php');

            $SMS = new SMS; // SMS 연결
            $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $config['cf_icode_server_port']);
            $SMS->Add($receive_number, $send_number, $config['cf_icode_id'], iconv_euckr(stripslashes($sms_contents)), "");
            $SMS->Send();
            $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
        }
	}

// 생성된 인증번호를 세션에 저장함
set_session("ss_hp_certify_number", $certify_number); 

$mb_datetime     = sql_password($mb['mb_datetime']);

$sql = " update {$g5['member_table']} 
            set mb_lost_certify = '$certify_number'
          where mb_id = '$mb[mb_id]' ";
sql_query($sql);

	
}else{
    alert_close("휴대폰 인증처리를 사용하실 수 없습니다.");
	exit;	
}

// SMS END  -------------------------------------------------------- 

if ($mb['mb_id']) 
{
    echo "<script language=\"JavaScript\">";
    echo "alert(\"'{$mb_name}'님의 휴대폰으로 인증번호를 전송하였습니다.\\n\\n인증번호를 확인 후 입력하여 주십시오.\");";
    echo "parent.document.getElementById(\"mb_datetime\").value = '$mb_datetime';";
    echo "parent.document.getElementById(\"mb_no\").value = '$mb[mb_no]';";	
	//echo "parent.document.getElementById(\"mb_hp_certify\").value = '$certify_number';";	
    echo "window.close();";
    echo "</script>";
}
?>