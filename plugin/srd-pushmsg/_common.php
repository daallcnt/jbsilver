<?php
include_once('../../common.php');

//해당일이 글쓴날에서 얼마나 지났는지를 리턴 
function srd_date_return ($datetime) {
	//그누보드 익명닉네임 이 알려주신 팁
	$_timestamp = array(86400*365, 86400*31, 86400, 3600, 60, 1); 
	$_timetitle = array("년 전", "개월 전", "일 전", "시간 전", "분 전", "초 전"); 

	$d = strtotime($datetime); 

	foreach($_timestamp as $key => $value) 
	if($d <= time() - $value) return (int)((time() - $d)/$_timestamp[$key]).$_timetitle[$key]; 
}

function srd_pushmsg_del ($del_day) {
	if ($del_day != 0) { // 해당일이 0이면 자동삭제 기능을 사용하지 않음
		$del_time =  date("Y-m-d", strtotime("-{$del_day}day")).' 00:00:00';
		$sql = "
			delete from g5_srd_pushmsg where msg_wdate < '{$del_time}' and msg_check != 'd' 
		";
		@sql_query($sql);
	}
}

$now_dated = date('Y-m-d H:i:s', time());

$now_dated2 = date('Y-m-d', time());

$g5['g5_srd_pushmsg'] = 'g5_srd_pushmsg';

?>
